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
        'password','id','created_at','updated_at'
    ];

    protected $appends= [
        'profile_icon'
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
            return $this->hasMany('App\Tweet'); // Relationを書く
        }

        return $this->hasMany('App\Tweet')->orderBy('created_at', "desc");
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function getProfileIconAttribute()
    {
        return $this->profile->profile_icon;
    }
}
