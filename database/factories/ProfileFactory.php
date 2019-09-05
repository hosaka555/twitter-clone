<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        "username" => "ユーザーネームさんです",
        "introduction" => "自己紹介です。なかなかうまくいきません...",
        "header_icon" => null,
        "profile_icon" => null
    ];
});
