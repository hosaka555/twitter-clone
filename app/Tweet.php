<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ["message","user_id"];

    protected $appends = ['account_id'];

    public function getAccountIdAttribute()
    {
        return auth()->user()->account_id;
    }
}
