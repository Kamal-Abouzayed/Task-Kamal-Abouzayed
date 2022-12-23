<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostRequest;
use App\Http\Requests\Api\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Notifications\PostNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = auth()->user();

        $posts = $user->posts;

        return $this->apiResponse('', PostResource::collection($posts), 200);
    }

    public function create(PostRequest $request)
    {
        $user = auth()->user();

        $data = $request->except('tag_id');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts');
        }

        $data['user_id'] = $user->id;

        $post = Post::create($data);

        if ($post) {

            $tags = $request->tag_id;

            $post->tags()->attach($tags);

            $post->notify(new PostNotification());

            return $this->apiResponse('Your post added successfully', new PostResource($post), 201);
        } else {
            return $this->apiResponse('Something went wrong while creating your post, Please try again', [], 422);
        }
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $user = auth()->user();

        $data = $request->except('tag_id');

        $post = Post::firstWhere([['id', $id], ['user_id', $user->id]]);

        if ($post) {

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts');
            } else {
                $data['image'] = $post->image;
            }

            $post->update($data);

            $tags = $request->tag_id;

            $post->tags()->sync($tags);

            return $this->apiResponse('Your post added successfully', new PostResource($post), 201);
        } else {
            return $this->apiResponse('Post not found', [], 404);
        }
    }

    public function delete($id)
    {
        $user = auth()->user();

        $post = Post::firstWhere([['id', $id], ['user_id', $user->id]]);

        if ($post) {

            Storage::delete($post->image);

            $post->delete();

            return $this->apiResponse('Your post deleted successfully', [], 200);
        } else {
            return $this->apiResponse('Not found', [], 404);
        }

    }

    public function deletedPosts()
    {
        $user = auth()->user();

        $posts = $user->posts()->withTrashed()->get();

        return $this->apiResponse('', PostResource::collection($posts), 200);
    }

    public function restorePost($id)
    {
        $user = auth()->user();

        $post = Post::firstWhere([['id', $id], ['user_id', $user->id]]);

        if ($post) {

            $post->restore();

            return $this->apiResponse('Your post restored successfully', new PostResource($post), 200);
        } else {
            return $this->apiResponse('Post not found', [], 404);
        }
    }

}
