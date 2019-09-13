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
        $this->assertSame(Tweet::where("user_id", $user->id)->get()->toJson(), $response->original);
    }

    public function test_get_a_tweet()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
            $tweet = factory(Tweet::class)->make();
            $user->tweets()->save($tweet);
        });
        $tweet = $user->tweets->first();
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
        $tweets = Tweet::whereIn('user_id', [$user->id, $general_user->id])->orderBy('created_at', 'desc')->get();
        $this->assertSame(json_encode($tweets), $response->original);
    }
}
