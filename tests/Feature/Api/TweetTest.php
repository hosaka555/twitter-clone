<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Tweet;

class TweetTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_post_tweet()
    {
        $user = factory(User::class)->create();
        $tweet = factory(Tweet::class)->make();

        $response = $this->post(route("api.post_tweet",["account_id" => $user->account_id]),[
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

        $response = $this->withoutMiddleware("auth:api")->post(route("api.post_tweet",["account_id" => $user->account_id]),[
            "user_id" => $user->id,
            "message" => $tweet->message,
        ]);
/* TODO validationチェックを行う
Vue側の処理をつくる
画像投稿のバックエンドを記述する
Vue側でも画像投稿できるようにする
*/
        $this->assertDatabaseMissing("tweets",["message" => $tweet->message]);
        $response->assertStatus(422);
    }
}
