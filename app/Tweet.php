<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tweet extends Model
{
    protected $fillable = ["message", "user_id"];

    protected $appends = ['account_id', "profile_icon", "username", "is_liked", "likes_count", 'image_url_lists'];

    protected $hidden = [
        "images", "likes"
    ];

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

    public function images()
    {
        return $this->hasMany('App\TweetImage', 'tweet_id', 'id')->orderBy('id'); // TweetモデルのidをTweetImageモデルのtweet_idに紐づける。
    }

    public function getImageUrlListsAttribute()
    {
        $images = [];
        $images = $this->images->map(function ($image) {
            $dirPath = "images/tweet/";
            if (Storage::cloud()->exists($filePath = $dirPath . $image->filename)) {
                return Storage::cloud()->url($filePath);
            }
        });

        return $images->toArray();
    }
}
