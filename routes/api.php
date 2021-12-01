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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('comment', 'Api\CommentController@index');
    Route::get('comment/{id}', 'Api\CommentController@show');
    Route::get('comment/post/{post_id}', 'Api\CommentController@showInPost');
    Route::post('comment', 'Api\CommentController@store');
    Route::post('comment/{post_id}/{user_id}', 'Api\CommentController@storeInPost');
    Route::put('comment/{id}', 'Api\CommentController@update');
    Route::delete('comment/{id}', 'Api\CommentController@destroy');
});