<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;

class LoginTest extends TestCase
{
    public function test_return_response_success()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_can_login_with_correct_credentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'password')
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(302)->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_redirect_home_when_authenticated_user_access()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/login');

        $response->assertStatus(302)->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_fail_with_incorrect_password()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'buzzword'
        ]);

        # LoginControllerにsendFailedLoginResponseを記述しないと、homeにリダイレクトされてしまう。ブラウザからやると、この記述がなくても動作する。原因を調べる。
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_user_cannot_access_home_when_not_authenticated()
    {
        $this->withExceptionHandling();
        $response = $this->get(route('home'));

        $response->assertRedirect('/login');
    }
}
