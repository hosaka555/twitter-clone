<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;

class RegisterTest extends TestCase
{
    public function test_return_response_success()
    {
        $response = $this->get('/signup');

        $response->assertStatus(200);
    }

    public function test_can_signup()
    {
        $user = factory(User::class)->make(['password' => "password"]);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ]));

        $this->assertDatabaseHas('users', [
            'account_id' => $user->account_id,
            'email' => $user->email,
        ]);

        $response->assertStatus(302)->assertRedirect(route('home'));

        $this->assertAuthenticatedAs(User::where('email', $user->email)->first());
    }

    public function test_redirect_home_when_authenticated_user_access()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/signup');

        $response->assertStatus(302)->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_cannot_register_with_dubblicate_account_id()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->create(['password' => "password"]);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => "original@test.com",
            'password' => "foobar",
            'password_confirmation' => "foobar"
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $this->assertGuest();
    }

    public function test_cannot_register_with_dubblicate_email()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->create(['password' => "password"]);

        $response = $this->post(route('auth.register', [
            'account_id' => "original_id",
            'email' => $user->email,
            'password' => "foobar",
            'password_confirmation' => "foobar"
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $this->assertGuest();
    }

    public function test_cannot_signup_with_min_password()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->make(['password' => "pass"]);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $this->assertGuest();
    }
}
