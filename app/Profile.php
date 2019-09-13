<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
        'username', 'introduction', 'header_icon', 'profile_icon'
    ];

    protected $hidden = [
        "user_id", "updated_at", "created_at", "id"
    ];

    public function getProfileIconAttribute($profile_icon)
    {
        if ($profile_icon) {
            $filePath = "images/profileIcon/" . $profile_icon;
            return  Storage::cloud()->url($filePath);
        } else {
            return asset('img/profile/default_profile.png');
        }
    }

    public function getHeaderIconAttribute($header_icon)
    {
        if ($header_icon) {
            $filePath = "images/headerIcon/" . $header_icon;
            return Storage::cloud()->url($filePath);
        } else {
            return asset('img/profile/default_header.png');
        }
    }

    public function updateProfile($request, $headerIconImage, $profileIconImage)
    {
        $profile_attr = [
            'username' => $request->request->get('username'),
            'introduction' => $request->request->get('introduction'),
        ];

        if (!!$request->changeHeaderIcon && !!$request->changeProfileIcon) {
            $images = [
                'header_icon' => $headerIconImage->filename,
                'profile_icon' => $profileIconImage->filename,
            ];
            $profile_attr = array_merge($profile_attr, $images);
        } elseif (!!$request->changeHeaderIcon) {
            $images = ['header_icon' => $headerIconImage->filename];
            $profile_attr = array_merge($profile_attr, $images);
        } elseif (!!$request->changeProfileIcon) {
            $images = ['profile_icon' => $profileIconImage->filename];
            $profile_attr = array_merge($profile_attr, $images);
        }

        $this->update($profile_attr);
    }
}
