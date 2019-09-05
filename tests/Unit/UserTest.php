<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Profile;

class UserTest extends TestCase
{
    public function test_can_create_User_with_profile()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->make(["introduction" => '']);
        $user->profile()->save($profile);


        $this->assertDatabaseHas('users', ['account_id' => $user->account_id])->assertDatabaseHas('profiles', ['username' => $profile->username]);
    }


    public function test_can_get_user_profile_icon()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->make(["profile_icon" => "test.png"]);
        $user->profile()->save($profile);

        $this->assertSame(env('MINIO_ENDPOINT').'/data/images/profileIcon/test.png',$user->profile_icon);
    }
}
