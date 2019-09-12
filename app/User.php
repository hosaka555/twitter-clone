<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Profile;

class User extends Authenticatable implements JWTSubject
{
    // use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'profile_icon',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tweets(int $query = 0)
    {
        if ($query) {
            $ids = $this->getFollowees()->pluck('followed_id')->all();
            array_push($ids, $this->id);
            return Tweet::whereIn('user_id', $ids)->orderBy('created_at', "desc");
        }

        return $this->hasMany('App\Tweet')->orderBy('created_at', "desc");
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function relationships()
    {
        return $this->belongsToMany(self::class, 'relation_ships', 'follower_id', 'followed_id')->withTimestamps();
    }

    public function getProfileIconAttribute()
    {
        return $this->profile->profile_icon;
    }

    public function follow($followed)
    {
        $this->relationships()->sync([$followed->id], false);
    }

    public function following($followed)
    {
        return !!$this->relationships()->where('followed_id', $followed->id)->first();
    }

    public function unfollow($followed)
    {
        $this->relationships()->detach($followed);
    }

    public function getFollowees()
    {
        return $this->relationships()->where('follower_id', $this->id);
    }
}
