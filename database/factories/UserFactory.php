<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'account_id' => $faker->name,
        'email' => "user@example.com",
        // 'email_verified_at' => now(),
        'password' => bcrypt('password'), // password
        // 'remember_token' => Str::random(10),
    ];
});

$factory->defineAs(User::class, 'general', function () use ($factory) {
    $user = $factory->raw(User::class);

    return array_merge(
        $user,
        [
            'account_id' => "general_account",
            "email" => "general@example.com"
        ]
    );
});
