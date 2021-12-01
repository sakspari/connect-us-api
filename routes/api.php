<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Api\VerifyEmailController;
use App\Http\Controllers\VerifyEmailController;
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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
//Route::post('login', 'Api\AuthController@login')->middleware(['middleware'=>'verified']);

// Verify email
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/success', function () {
    return view('mail');
});

Route::group(['middleware'=>'auth:api'],function(){ //setelah login baru bisa dijalankan
    Route::get('users/{id}','Api\AuthController@show');
    Route::put('users/{id}','Api\AuthController@update');
    Route::delete('users/{id}','Api\AuthController@destroy');
});
