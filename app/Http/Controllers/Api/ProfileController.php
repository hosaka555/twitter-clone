<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Profile;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\ProfileImage as Image;
use DB;
use App\Http\Requests\Api\PostProfileImageRequest;

class ProfileController extends Controller
{
    public function index($account_id)
    {
        $profile = User::where('account_id', $account_id)->first()->profile->toArray();

        $profile = array_merge($profile, ["account_id" => $account_id]);
        return response()->json($profile, 200);
    }

    public function edit()
    {
        $profile = Auth::user()->profile->toJson();

        return response()->json($profile, 200);
    }

    public function update(PostProfileImageRequest $request)
    {
        $headerIconImage = new Image($request->header_icon);
        $profileIconImage = new Image($request->profile_icon);

        if ($headerIconImage->filename) {
            Storage::cloud()->putFileAs('images/headerIcon', $request->header_icon, $headerIconImage->filename, 'public');
        }

        if ($profileIconImage->filename) {
            Storage::cloud()->putFileAs('images/profileIcon', $request->profile_icon, $profileIconImage->filename, 'public');
        }

        DB::beginTransaction();
        try {
            Auth::user()->profile->updateProfile($request, $headerIconImage, $profileIconImage);

            DB::commit();
            return response()->json([], 204);
        } catch (\Exception $exception) {
            Storage::cloud()->delete($headerIconImage->filename);
            Storage::cloud()->delete($profileIconImage->filename);

            throw $exception;
        }
    }
}
