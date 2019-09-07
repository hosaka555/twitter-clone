<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ["message", "user_id"];

    protected $appends = ['account_id', "profile_icon", "username"];

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
}
