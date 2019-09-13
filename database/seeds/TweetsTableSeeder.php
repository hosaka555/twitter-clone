<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tweet;

class TweetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::find(1)->each(function ($user) {
            for($i=0;$i<10;$i++){
                $user->tweets()->save(factory(Tweet::class)->make());
            }
        });
    }
}
