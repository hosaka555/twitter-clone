<?php

use App\Http\Controllers\Api\ProfileController;
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

    Route::get('users', 'UserController@index')->name('api.get_users');
    Route::prefix("users/{account_id}")->group(function () {

        Route::get("/", "ProfileController@index")->name("api.get_profile");

        Route::put("/follow", "RelationshipController@follow")->name("api.follow");
        Route::delete("/unfollow", "RelationshipController@unfollow")->name("api.unfollow");
        Route::get("/followees", "RelationshipController@followees")->name("api.get_followees");

        Route::get("/edit", "ProfileController@edit")->name("api.edit_profile");

        Route::post("/edit", "ProfileController@update")->name("api.update_profile");

        Route::prefix("tweets")->group(function () {
            Route::post("tweet", "TweetController@create")->name("api.post_tweet");

            Route::get("/", "TweetController@index")->name("api.get_tweets");
            Route::get("/{tweet_id}", "TweetController@showTweet")->name("api.get_tweet");
        });
    });
});
