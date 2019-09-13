<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Tweet;

$factory->define(Tweet::class, function (Faker $faker) {
    return [
        "message" => "This is a first tweet message.\n".$faker->text
    ];
});

$factory->defineAs(Tweet::class, 'test', function () use ($factory) {
    $tweet = $factory->raw(Tweet::class);

    return array_merge(
        $tweet,
        [
            "message" => "This is a test tweet."
        ]
    );
});
