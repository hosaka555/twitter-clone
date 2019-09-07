<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;
use App\Profile;

class UsersTest extends TestCase
{
    use WithoutMiddleware;

    public function test_can_get_all_user()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        factory(User::class, "seeder", 10)->create()->each(function ($user) {
            $profile = factory(Profile::class, "seeder")->make();
            $user->profile()->save($profile);
        });

        $response = $this->actingAs($user)->get(route('api.get_users'));

        $response->assertStatus(200);
        $this->assertSame(User::whereNotIN('id', [$user->id])->orderBy('id','desc')->get()->toJson(), $response->original);
    }
}
