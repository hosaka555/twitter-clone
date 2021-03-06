<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNotIN('id', [auth()->user()->id])->with('profile')->orderBy('id', 'desc')->get()->toJson();
        return response()->json($users, 200);
    }
}
