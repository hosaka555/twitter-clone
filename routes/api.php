<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->namespace('Api')->group(function () {
    Route::get('me', 'AuthController@me');

    Route::prefix("users/{account_id}")->group(function () {
        Route::post("tweets/tweet","TweetController@create")->name("api.post_tweet");
    });
});