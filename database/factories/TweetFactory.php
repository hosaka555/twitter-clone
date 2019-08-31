<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Tweet;

$factory->define(Tweet::class, function (Faker $faker) {
    return [
        "message" => "This is a first tweet message."
    ];
});
