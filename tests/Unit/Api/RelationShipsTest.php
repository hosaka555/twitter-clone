<?php

namespace Tests\Unit\Api;

use App\Api\Relationship;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class RelationShipsTest extends TestCase
{
    protected $user;
    protected $another_user;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(\App\Profile::class)->make();
            $user->profile()->save($profile);
        });

        $this->another_user = tap(factory(User::class, 'general')->create(), function ($user) {
            $profile = factory(\App\Profile::class)->make();
            $user->profile()->save($profile);
        });
    }
    public function test_can_follow_another_user()
    {
        $this->user->follow($this->another_user);
        $this->assertDatabaseHas('relation_ships', ["follower_id" => $this->user->id, "followed_id" => $this->another_user->id]);
        $this->assertTrue($this->user->following($this->another_user));
    }

    public function test_can_unfollow_another_user()
    {
        $this->user->follow($this->another_user);
        $this->assertTrue($this->user->following($this->another_user));

        $this->user->unfollow($this->another_user);
        $this->assertDatabaseMissing('relation_ships', ["follower" => $this->user->id, "followed" => $this->another_user->id]);
        $this->assertFalse($this->user->following($this->another_user));
    }

    public function test_does_not_occur_error_when_unfollow_no_following_user()
    {
        $this->user->unfollow($this->another_user);
        $this->assertFalse($this->user->following($this->another_user));
    }

    public function test_does_not_insert_duplication_data()
    {
        $this->user->follow($this->another_user);
        $this->user->follow($this->another_user);
        $this->assertTrue($this->user->following($this->another_user));

        $this->assertSame(1, sizeof(\App\Relationship::where('followed_id', $this->another_user->id)->get()));
    }
}
