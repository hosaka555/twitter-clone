<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Tweet;
use App\Profile;

class TweetsTest extends TestCase
{
    public function test_can_save_post_tweet()
    {
        $user = factory(User::class)->create();
        $tweet = factory(Tweet::class)->make();

        $user->tweets()->save($tweet);

        $this->assertDatabaseHas('tweets', ['message' => $tweet->message]);
    }

    public function test_can_get_my_tweets()
    {
        $user = factory(User::class)->create();

        for ($i = 0; $i < 3; $i++) {
            $tweet = factory(Tweet::class)->make(["message" => "This is $i message"]);

            $user->tweets()->save($tweet);
        };

        $lastTweet = $user->tweets->last();

        $this->assertSame($tweet->message, $lastTweet->message);
    }

    public function test_can_get_profile_icon_from_property()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $customImage = "http://localhost/img/profile/custom_profile.png";
            $profile = factory(Profile::class)->make(["profile_icon" => $customImage]);
            $user->profile()->save($profile);
            $tweet = factory(Tweet::class)->make(["message" => "This is first message"]);
            $user->tweets()->save($tweet);
        });

        $tweet = Tweet::first();
        $this->assertSame($user->profile->profile_icon, $tweet->profile_icon);
    }

    public function test_can_get_username_from_property()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            $tweet = factory(Tweet::class)->make(["message" => "This is first message"]);
            $user->tweets()->save($tweet);
        });

        $tweet = Tweet::first();
        $this->assertSame($user->profile->username, $tweet->username);
    }
}
