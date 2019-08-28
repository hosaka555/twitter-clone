<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Tweet;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;

class TweetTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_user_can_post_tweet()
    {
        $user = factory(User::class)->create(["password"=> bcrypt("password")]);
        $tweet = factory(Tweet::class)->make();

        $response = $this->actingAs($user)->post(route("api.post_tweet",["account_id" => $user->account_id]),[
            "user_id" => $user->id,
            "message" => $tweet->message,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas("tweets",["message" => $tweet->message]);
    }

    public function test_cannot_post_tweet_when_message_has_141_characters()
    {
        $user = factory(User::class)->create();

        $message = str_repeat("a",141);
        $tweet = factory(Tweet::class)->make(["message" => $message]);

        $response = $this->post(route("api.post_tweet",["account_id" => $user->account_id]),[
            "user_id" => $user->id,
            "message" => $tweet->message,
        ]);

        $this->assertDatabaseMissing("tweets",["message" => $tweet->message]);
        $response->assertStatus(422);
    }
}
