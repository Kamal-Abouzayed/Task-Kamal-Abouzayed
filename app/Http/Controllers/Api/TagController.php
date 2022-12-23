<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $tags = Tag::all();

        return $this->apiResponse('', TagResource::collection($tags), 200);
    }

    public function create(TagRequest $request)
    {
        $user = auth()->user();

        $data = $request->all();

        $tag = $user->tags()->create($data);

        return $this->apiResponse('Tag created successfully', new TagResource($tag), 200);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $tag = Tag::firstWhere([['id', $id], ['user_id', $user->id]]);

        if ($tag) {

            $data = $request->all();

            $tag->update($data);

            return $this->apiResponse('Tag updated successfully', new TagResource($tag), 200);

        } else {
            return $this->apiResponse('Tag not found', [], 404);
        }
    }

    public function delete($id)
    {

        $user = auth()->user();

        $tag = Tag::firstWhere([['id', $id], ['user_id', $user->id]]);

        if ($tag) {

            $tag->delete();

            return $this->apiResponse('Tag deleted successfully', [], 200);

        } else {
            return $this->apiResponse('Tag not found', [], 404);
        }
    }
}
