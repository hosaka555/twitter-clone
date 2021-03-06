<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Tweet;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TweetTest extends TestCase
{
    use WithoutMiddleware;

    public function test_user_can_post_tweet()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });
        $tweet = factory(Tweet::class, "test")->make();

        $response = $this->actingAs($user)->post(route("api.post_tweet", ["account_id" => $user->account_id]), [
            "message" => $tweet->message,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("tweets", ["message" => $tweet->message]);
    }

    public function test_cannot_post_tweet_when_message_has_141_characters()
    {
        $user = factory(User::class)->create();

        $message = str_repeat("a", 141);
        $tweet = factory(Tweet::class)->make(["message" => $message]);

        $response = $this->post(route("api.post_tweet", ["account_id" => $user->account_id]), [
            "user_id" => $user->id,
            "message" => $tweet->message,
        ]);

        $this->assertDatabaseMissing("tweets", ["message" => $tweet->message]);
        $response->assertStatus(422);
    }

    public function test_return_json_with_my_tweets()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->make();
        $user->profile()->save($profile);

        for ($i = 0; $i < 2; $i++) {
            $tweet = factory(Tweet::class)->make(["message" => "This is $i message"]);

            $user->tweets()->save($tweet);
        };

        $response = $this->actingAs($user)->get(route_with_query("api.get_tweets", ["account_id" => $user->account_id], ["include_relations" => 0]));

        $response->assertStatus(200);
        $this->assertSame(Tweet::where("user_id", $user->id)->with('likes', 'images')->get()->toJson(), $response->original);
    }

    public function test_get_a_tweet()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            $tweet = factory(Tweet::class)->make();
            $user->tweets()->save($tweet);
        });
        $tweet = $user->tweets()->with('likes', 'images')->first();
        $response = $this->actingAs($user)->get(route_with_query("api.get_tweet", [
            "account_id" => $user->account_id,
            "tweet_id" => $tweet->id
        ]));

        $response->assertStatus(200);
        $this->assertSame($tweet->toJson(), $response->original);
    }

    public function test_can_get_tweets_with_followees_tweets()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            for ($i = 0; $i < 2; $i++) {
                $tweet = factory(Tweet::class)->make(["message" => "This is $i message"]);

                $user->tweets()->save($tweet);
            };
        });

        $general_user = tap(factory(User::class, 'general')->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            for ($i = 0; $i < 2; $i++) {
                $tweet = factory(Tweet::class)->make(["message" => "This is $i message"]);

                $user->tweets()->save($tweet);
            };
        });

        $user->follow($general_user);

        $response = $this->actingAs($user)->get(route_with_query("api.get_tweets", ["account_id" => $user->account_id], ["include_relations" => 1]));

        $response->assertStatus(200);
        $tweets = Tweet::whereIn('user_id', [$user->id, $general_user->id])->with('likes', 'images')->orderBy('created_at', 'desc')->get();
        $this->assertSame(json_encode($tweets), $response->original);
    }

    public function test_user_can_post_tweet_with_four_images()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });
        $tweet = factory(Tweet::class, "test")->make();
        Storage::fake();
        $images = [];
        for ($i = 0; $i < 4; $i++) {
            $images[] = UploadedFile::fake()->image('image{$i}.jpg');
        }

        $response = $this->actingAs($user)->post(route("api.post_tweet", ["account_id" => $user->account_id]), [
            "message" => $tweet->message,
            "images" => $images
        ]);

        $response->assertStatus(200);

        $expected_tweet = Tweet::first();
        $this->assertDatabaseHas("tweets", ["message" => $expected_tweet->message]);
        $this->assertSame(4, $expected_tweet->images->count());

        $pattern = '/^(.+)$/';

        foreach (\App\TweetImage::all() as $index => $image) {
            preg_match($pattern, $image->filename, $mathces);
            $filename = $mathces[1];
            Storage::cloud()->assertExists("images/tweet/" . $filename);
            Storage::cloud()->delete("images/tweet/" . $filename);
        }
    }

    public function test_user_can_get_tweet_with_images()
    {
        $images = [];

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

        $response = $this->actingAs($user)->get(route_with_query("api.get_tweets", ["account_id" => $user->account_id], ["include_relations" => 0]));

        $response->assertStatus(200);
        $this->assertSame(Tweet::where("user_id", $user->id)->with('likes', 'images')->get()->toJson(), $response->original);
    }

    public function test_will_be_invalid_more_than_five_images()
    {
        $this->withExceptionHandling();

        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });
        $tweet = factory(Tweet::class, "test")->make();
        Storage::fake();
        $images = [];
        for ($i = 0; $i < 5; $i++) {
            $images[] = UploadedFile::fake()->image('image{$i}.jpg');
        }

        $response = $this->actingAs($user)->post(route("api.post_tweet", ["account_id" => $user->account_id]), [
            "message" => $tweet->message,
            "images" => $images
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing("tweets", ["message" => $tweet->message]);
    }
}
