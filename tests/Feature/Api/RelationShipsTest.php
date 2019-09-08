<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RelationShipsTest extends TestCase
{
    use WithoutMiddleware;

    protected $user;
    protected $another_user;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = tap(factory(\App\User::class)->create(), function ($user) {
            $profile = factory(\App\Profile::class)->make();
            $user->profile()->save($profile);
        });

        $this->another_user = tap(factory(\App\User::class, 'general')->create(), function ($user) {
            $profile = factory(\App\Profile::class)->make();
            $user->profile()->save($profile);
        });
    }

    public function test_can_follow_another_user()
    {
        $response = $this->actingAs($this->user)->put(route(
            'api.follow',
            [
                'account_id' => $this->another_user->account_id
            ]
        ));

        $response->assertStatus(204);
        $this->assertDatabaseHas('relation_ships', ["follower_id" => $this->user->id, "followed_id" => $this->another_user->id]);
        $this->assertTrue($this->user->following($this->another_user));
    }

    public function test_can_unfollow_another_user()
    {
        $this->user->follow($this->another_user);
        $this->assertTrue($this->user->following($this->another_user));

        $response = $this->actingAs($this->user)->delete(route(
            'api.unfollow',
            [
                'account_id' => $this->another_user->account_id
            ]
        ));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('relation_ships', ["follower" => $this->user->id, "followed" => $this->another_user->id]);
        $this->assertFalse($this->user->following($this->another_user));
    }

    public function test_return_response_500_when_follow_invalid_user()
    {
        $this->withExceptionHandling();
        $invalid_account_id = 9999;
        $response = $this->actingAs($this->user)->put(route(
            'api.follow',
            [
                'account_id' => $invalid_account_id,
            ]
        ));

        $response->assertStatus(404);
        $this->assertDatabaseMissing('relation_ships', ["follower" => $this->user->id, "followed" => $invalid_account_id]);
    }

    public function test_return_response_500_when_unfollow_invalid_user()
    {
        $this->withExceptionHandling();
        $invalid_account_id = 9999;
        $response = $this->actingAs($this->user)->delete(route(
            'api.unfollow',
            [
                'account_id' => $invalid_account_id,
            ]
        ));

        $response->assertStatus(404);
        $this->assertDatabaseMissing('relation_ships', ["follower" => $this->user->id, "followed" => $invalid_account_id]);
    }
}
