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

Route::group(['middleware'=>'auth:api'],function(){ //setelah login baru bisa dijalankan
    Route::get('users/{id}','Api\AuthController@show');
    Route::put('users/{id}','Api\AuthController@update');
    Route::delete('users/{id}','Api\AuthController@destroy');
    
    Route::get('followers/{id}', 'Api\FollowersController@show');
    Route::get('followers/find/{id}', 'Api\FollowersController@find');
    Route::delete('followers/{id}', 'Api\FollowersController@destroy');
    Route::post('followers', 'Api\FollowersController@store');
    
    Route::get('followers/{id}', 'Api\FollowersController@show');
    Route::get('followers/find/{id}', 'Api\FollowersController@find');
    Route::delete('followers/{id}', 'Api\FollowersController@destroy');
    Route::post('followers', 'Api\FollowersController@store');

    Route::get('post/{id}', 'Api\PostController@show');
    Route::delete('post/{id}', 'Api\PostController@destroy');
    Route::post('post', 'Api\PostController@store');
    Route::put('post/{id}', 'Api\PostController@update');
});
