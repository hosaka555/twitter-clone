<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RelationshipController extends Controller
{
    public function follow($account_id)
    {
        $user = User::where('account_id',$account_id)->firstOrFail();

        auth()->user()->follow($user);
        return response()->json([],204);
    }

    public function unfollow($account_id)
    {
        $user = User::where('account_id',$account_id)->firstOrFail();

        auth()->user()->unfollow($user);
        return response()->json([],204);
    }

    public function followees($account_id)
    {
        $user = User::where('account_id',$account_id)->firstOrFail();
        $followees = $user->getFollowees();
        return response()->json($followees->pluck('followed_id'),200);
    }
}
