<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Profile;

class ProfileTest extends TestCase
{
    public function test_return_defalt_profile_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        $this->assertSame("http://localhost/img/profile/default_profile.png", $user->profile->profile_icon);
    }

    public function test_return_defalt_header_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        $this->assertSame("http://localhost/img/profile/default_header.png", $user->profile->header_icon);
    }

    public function test_return_user_profile_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $customImage = "custom_profile.png";
            $profile = factory(Profile::class)->make(["profile_icon" => $customImage]);
            $user->profile()->save($profile);
        });

        $this->assertSame(env('MINIO_ENDPOINT').'/data/images/profileIcon/custom_profile.png', $user->profile->profile_icon);
    }

    public function test_return_user_header_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $customImage = "custom_header.png";
            $profile = factory(Profile::class)->make(["header_icon" => $customImage]);
            $user->profile()->save($profile);
        });

        $this->assertSame(env('MINIO_ENDPOINT').'/data/images/headerIcon/custom_header.png', $user->profile->header_icon);
    }
}
