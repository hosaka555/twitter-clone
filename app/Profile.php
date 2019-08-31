<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'username', 'introduction', 'header_icon', 'profile_icon'
    ];
}
