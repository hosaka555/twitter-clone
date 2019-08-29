<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;

class LogoutTest extends TestCase
{
    public function test_user_can_logout()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/home');
        $response->assertStatus(200)->assertViewIs('home.index');

        $response = $this->actingAs($user)->post(route('auth.logout'));
        $response->assertStatus(302)->assertRedirect(route('root'));
        $this->assertGuest();
    }
}
