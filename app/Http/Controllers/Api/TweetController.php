<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostTweetRequest;
use App\Tweet;

class TweetController extends Controller
{
    public function create(PostTweetRequest $request)
    {
        $tweet = new Tweet($request->request->all());

        $tweet->save();

        return response()->json(200);
    }
}
