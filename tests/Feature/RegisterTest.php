<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Profile;

class RegisterTest extends TestCase
{
    public function test_return_response_success()
    {
        $response = $this->get('/signup');

        $response->assertStatus(200);
    }

    public function test_can_signup_with_profile()
    {
        $user = factory(User::class)->make(['password' => "password"]);
        $profile = factory(Profile::class)->make(["introduction" => '']);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password,
            'username' => $profile->username
        ]));

        $this->assertDatabaseHas('users', [
            'account_id' => $user->account_id,
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('profiles', ["username" => $profile->username]);

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
        $profile = factory(Profile::class)->make(["introduction" => '']);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => "original@test.com",
            'password' => "foobar",
            'password_confirmation' => "foobar",
            'username' => $profile->username
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $this->assertGuest();
    }

    public function test_cannot_register_with_dubblicate_email()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->create(['password' => "password"]);
        $profile = factory(Profile::class)->make(["introduction" => '']);

        $response = $this->post(route('auth.register', [
            'account_id' => "original_id",
            'email' => $user->email,
            'password' => "foobar",
            'password_confirmation' => "foobar",
            'username' => $profile->username
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $this->assertGuest();
    }

    public function test_cannot_signup_with_min_password()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->make(['password' => "pass"]);
        $profile = factory(Profile::class)->make(["introduction" => '']);

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password, 'username' => $profile->username
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $errors = session('errors');
        $response->assertSessionHasErrors();
        $this->assertEquals('The password must be at least 6 characters.', $errors->get('password')[0]);
        $this->assertGuest();
    }


    public function test_cannot_signup_without_username()
    {
        $this->withExceptionHandling();
        $this->get('/signup');

        $user = factory(User::class)->make();

        $response = $this->post(route('auth.register', [
            'account_id' => $user->account_id,
            'email' => $user->email,
            'password' => $user->password,
            'password_confirmation' => $user->password
        ]));

        $response->assertStatus(302)->assertRedirect('/signup');
        $errors = session('errors');
        $response->assertSessionHasErrors();
        $this->assertEquals('The username field is required.', $errors->get('username')[0]);

        $this->assertGuest();
    }
}
