<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class LikesTest extends TestCase
{
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setup();
        $this->user = tap(factory(\App\User::class)->create(), function ($user) {
            $profile = factory(\App\Profile::class)->make();
            $user->profile()->save($profile);
            $tweet = factory(\App\Tweet::class)->make();
            $user->tweets()->save($tweet);
        });
        $this->tweet = \App\Tweet::first();
    }

    public function test_can_like_tweet()
    {
        $response = $this->actingAs($this->user)->put(route('api.like_tweet', [
            'account_id' => $this->user->account_id,
            'tweet_id' => $this->tweet->id,
        ]));

        $response->assertStatus(204);

        $this->assertDatabaseHas('likes', ["user_id" => $this->user->id, "tweet_id" => $this->tweet->id]);
        $this->assertSame(1,$this->tweet->likes()->count());
    }

    public function test_does_not_insert_duplication_data()
    {
        $this->tweet->likes()->sync([$this->user->id], false);
        $this->assertDatabaseHas('likes', ["user_id" => $this->user->id, "tweet_id" => $this->tweet->id]);
        $this->assertSame(1,$this->tweet->likes()->count());

        $response = $this->actingAs($this->user)->put(route('api.like_tweet', [
            'account_id' => $this->user->account_id,
            'tweet_id' => $this->tweet->id,
        ]));

        $response->assertStatus(204);
        $this->assertSame(1,$this->tweet->likes()->count());
    }

    public function test_can_unlike_tweet()
    {
        $this->tweet->likes()->sync([$this->user->id], false);
        $this->assertDatabaseHas('likes', ["user_id" => $this->user->id, "tweet_id" => $this->tweet->id]);
        $this->assertSame(1,$this->tweet->likes()->count());

        $response = $this->actingAs($this->user)->delete(route('api.unlike_tweet', [
            'account_id' => $this->user->account_id,
            'tweet_id' => $this->tweet->id,
        ]));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('likes', ["user_id" => $this->user->id, "tweet_id" => $this->tweet->id]);
        $this->assertSame(0,$this->tweet->likes()->count());
    }
}
