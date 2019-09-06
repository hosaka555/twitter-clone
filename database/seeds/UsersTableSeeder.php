<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create()->each(function ($user) {
            $profile = factory(App\Profile::class)->make();
            $user->profile()->save($profile);
        });

        factory(User::class, "seeder", 5)->create()->each(function ($user) {
            $profile = factory(App\Profile::class,"seeder")->make();
            $user->profile()->save($profile);
        });
    }
}
