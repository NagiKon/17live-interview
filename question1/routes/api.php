<?php

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

Route::middleware(['content-type.json'])->group(function () {
    Route::post('/posts', 'PostController@createPost');
    Route::put('/posts/{postId}', 'PostController@updateEntirePostById');
    Route::patch('/posts/{postId}', 'PostController@updatePostById');

    Route::post('/posts/{postId}/comments', 'CommentController@createComment');
    Route::put('/posts/{postId}/comments/{commentId}', 'CommentController@updateComment');
});

Route::get('/posts/{postId}', 'PostController@getPostById');
Route::get('/posts/{postId}/comments', 'PostController@getCommentListByPostId');
Route::delete('/posts/{postId}', 'PostController@deletePostById');

Route::get('/posts/{postId}/comments/{commentId}', 'CommentController@getCommentById');
Route::delete('/posts/{postId}/comments/{commentId}', 'CommentController@deleteCommentById');
