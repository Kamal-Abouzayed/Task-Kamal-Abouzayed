<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function stats()
    {
        $users = Cache::remember('users_count', 60*60*24, function () {
            return User::count();
        }); 

        $posts = Cache::remember('posts_count', 60*60*24, function () {
            return Post::count();
        });

        $users_without_posts = Cache::remember('users_without_posts', 60*60*24, function () {
            return  User::whereDoesntHave('posts')->count();
        });

        return response()->json([
            'users' => $users,
            'posts' => $posts,
            'users_without_posts' => $users_without_posts,
        ]);

    }
}
