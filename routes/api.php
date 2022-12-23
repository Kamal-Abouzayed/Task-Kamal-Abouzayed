<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {

    // Authentication
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('verify-user', 'AuthController@verifyUser');

    // Stats Routes
    Route::get('stats', 'StatsController@stats');

    Route::middleware('auth:sanctum')->group(function () {

        // Tags routes
        Route::get('tags', 'TagController@index');
        Route::post('create-tag', 'TagController@create');
        Route::post('update-tag/{id}', 'TagController@update');
        Route::post('delete-tag/{id}', 'TagController@delete');

        // Posts routes
        Route::get('posts', 'PostController@index');
        Route::post('create-post', 'PostController@create');
        Route::post('update-post/{id}', 'PostController@update');
        Route::post('delete-post/{id}', 'PostController@delete');
        Route::get('deleted-posts', 'PostController@deletedPosts');
        Route::post('restore-post/{id}', 'PostController@restorePost');
    });
});
