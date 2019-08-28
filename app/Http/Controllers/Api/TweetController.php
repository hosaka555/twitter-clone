<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostTweetRequest;
use Illuminate\Support\Facades\Auth;
use App\Tweet;

class TweetController extends Controller
{
    public function create(PostTweetRequest $request)
    {
        $tweet = new Tweet($request->request->all());
        $tweet -> user_id = auth()->user()->getKey();
        $tweet->save();

        return response()->json(200);
    }
}
