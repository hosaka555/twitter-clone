<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostTweetRequest;
use Illuminate\Support\Facades\Auth;
use App\Tweet;
use DB;

class TweetController extends Controller
{

    public function index(Request $request)
    {
        $q = $request->query();
        if(!$q){
            $q["include_relations"] = 0;
        }

        DB::enableQueryLog();
        $tweets = Auth::user($q["include_relations"])->tweets->toJson();
        // eval(\Psy\sh());
        return response()->json($data = $tweets,$status = 200);
    }

    public function create(PostTweetRequest $request)
    {
        $tweet = new Tweet($request->request->all());
        Auth::user()->tweets()->save($tweet);

        return response()->json(200);
    }
}
