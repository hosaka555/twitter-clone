<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ["message", "user_id"];

    protected $appends = ['account_id', "profile_icon", "username", "is_liked","likes_count"];

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function getAccountIdAttribute()
    {
        return $this->user->account_id;
    }

    public function getProfileIconAttribute()
    {
        return $this->user->profile->profile_icon;
    }

    public function getUsernameAttribute()
    {
        return $this->user->profile->username;
    }

    public function getIsLikedAttribute()
    {
        return $this->likes->contains(function ($user) {
            return $user->id === auth()->user()->id;
        });
    }

    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes');
    }
}
