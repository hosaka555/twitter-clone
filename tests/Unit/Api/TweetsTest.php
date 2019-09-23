<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Tweet;
use App\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function test_return_images()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            $tweet = factory(Tweet::class, "test")->make();
            $user->tweets()->save($tweet);
        });

        Storage::fake();
        $images = [];
        for ($i = 0; $i < 4; $i++) {
            UploadedFile::fake()->image($fileName = 'image' . $i . '.jpg');
            $images[] = ["filename" => $fileName];
        }

        UploadedFile::fake()->image($fileName = 'image{$i}.jpg');

        $tweet = Tweet::first();
        $tweet->images()->createMany($images);

        for ($i = 0; $i < 4; $i++) {
            $this->assertDatabaseHas('tweet_images', ["filename" => 'image' . $i . '.jpg']);
        }

        $this->assertSame(4, $tweet->images->count());
        $this->assertSame($images[0]['filename'],$tweet->images()->first()->filename);
    }
}
