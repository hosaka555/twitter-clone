<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PostTweetRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Tweet;
use DB;
use App\User;

class TweetController extends Controller
{

    public function index(Request $request, $account_id)
    {

        $q = intval($request->query('include_relations'));

        if (!$q) {
            $q = 0;
        }

        $tweets = User::where('account_id', $account_id)->with('profile')->firstOrFail()->tweets($q)->with('likes', 'images','user.profile')->get()->toJson();

        return response()->json($tweets, 200);
    }

    public function showTweet($account_id, $tweet_id)
    {
        $tweet = Tweet::where('id', $tweet_id)->with('likes', 'images')->firstOrFail()->toJson();
        return response()->json($tweet, 200);
    }

    public function create(PostTweetRequest $request)
    {
        $tweet_attr = ["message" => $request->message];
        $tweet = new Tweet($tweet_attr);
        Auth::user()->tweets()->save($tweet);

        if (!$images = $request->images) {
            return response()->json($data = $tweet->toJson(), 200);
        }

        $fileNameLists = [];
        foreach ($images as $image) {
            $tweetImage = new \App\Image($image);
            $fileNameLists[] = ['filename' => $tweetImage->filename];
            Storage::cloud()->putFileAs('images/tweet', $image, $tweetImage->filename, 'public');
        }

        DB::beginTransaction();
        try {
            $tweet->images()->createMany($fileNameLists);

            $tweet = Tweet::where('id', $tweet->id)->with('likes', 'images')->firstOrFail()->toJson();

            DB::commit();
            return response()->json($data = $tweet, 200);
        } catch (\Exception $exception) {
            foreach ($images as $index => $image) {
                Storage::cloud()->delete($fileNameLists[$index]['filename']);
            }

            throw $exception;
        }
    }

    public function like($account_id, $tweet_id)
    {
        $tweet = Tweet::where('id', $tweet_id)->with('likes')->firstOrFail();

        $tweet->likes()->sync([auth()->user()->id], false);
        return response()->json([], 204);
    }

    public function unlike($account_id, $tweet_id)
    {
        $tweet = Tweet::where('id', $tweet_id)->with('likes')->firstOrFail();

        $tweet->likes()->detach(auth()->user()->id);
        return response()->json([], 204);
    }
}
