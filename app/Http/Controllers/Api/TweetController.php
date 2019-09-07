<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostTweetRequest;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use DB;
use App\User;

class TweetController extends Controller
{

    public function index(Request $request,$account_id)
    {
        $q = $request->query();
        if (!$q) {
            $q["include_relations"] = 0;
        }

        $tweets = User::where('account_id',$account_id)->firstOrFail()->tweets->toJson();

        return response()->json($tweets, 200);
    }

    public function showTweet($account_id,$tweet_id)
    {
        $tweet = Tweet::where('id',$tweet_id)->firstOrFail()->toJson();
        return response()->json($tweet,200);
    }

    public function create(PostTweetRequest $request)
    {
        $tweet_attr = ["message" => $request->message];
        $tweet = new Tweet($tweet_attr);
        Auth::user()->tweets()->save($tweet);

        return response()->json($data = $tweet->toJson(), 200);
    }
}
