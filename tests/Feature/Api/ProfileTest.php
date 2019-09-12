<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User;
use App\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\ProfileImage;

class ProfileTest extends TestCase
{
    use WithoutMiddleware;

    public function test_return_user_profile_data()
    {
        $general_user = tap(factory(User::class, "general")->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route("api.get_profile", ["account_id" => $general_user->account_id]));

        $response->assertStatus(200);
        $profile = Profile::where('user_id', $general_user->id)->get()->first()->toArray();
        $account_id = $general_user->account_id;
        $isFollowing = $user->following($general_user);
        $this->assertSame(array_merge($profile, compact(['account_id','isFollowing'])), $response->original);
    }

    public function test_return_my_profile_data()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        $response = $this->actingAs($user)->get(route("api.edit_profile", ["account_id" => $user->account_id]));

        $response->assertStatus(200);
        $this->assertSame(Profile::where('user_id', $user->id)->get()->first()->toJson(), $response->original);
    }

    public function test_can_update_my_profile()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.jpg');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.jpg');

        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => $profile_icon_image,
            "changeHeaderIcon" => 1,
            "changeProfileIcon" => 1
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));

        $pattern = '/(?<=Icon\/)(.+)$/';

        preg_match($pattern, $profile->header_icon, $mathces);
        $headerIconName = $mathces[1];
        Storage::cloud()->assertExists("images/headerIcon/" . $headerIconName);

        preg_match($pattern, $profile->profile_icon, $mathces);
        $profileIconName = $mathces[1];
        Storage::cloud()->assertExists("images/profileIcon/" . $profileIconName);

        Storage::cloud()->delete("images/headerIcon/" . $headerIconName);
        Storage::cloud()->delete("images/profileIcon/" . $profileIconName);
    }

    public function test_update_his_profile_when_another_user_update_others_profile()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        $general_user = tap(factory(User::class, "general")->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.png');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.png');

        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => $profile_icon_image,
            "changeHeaderIcon" => 1,
            "changeProfileIcon" => 1
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $general_user->account_id]), $update_profile);
        $response->assertStatus(204);

        $profile = Profile::where('user_id', $general_user->id)->first();
        $this->assertSame($general_user->profile->only(['username', 'introduction']), $profile->only(['username', 'introduction']));
        $this->assertSame("http://localhost/img/profile/default_profile.png", $profile->profile_icon);
        $this->assertSame("http://localhost/img/profile/default_header.png", $profile->header_icon);

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));

        $pattern = '/(?<=Icon\/)(.+)$/';

        preg_match($pattern, $profile->header_icon, $mathces);
        $headerIconName = $mathces[1];
        Storage::cloud()->assertExists("images/headerIcon/" . $headerIconName);

        preg_match($pattern, $profile->profile_icon, $mathces);
        $profileIconName = $mathces[1];
        Storage::cloud()->assertExists("images/profileIcon/" . $profileIconName);
        Storage::cloud()->delete("images/headerIcon/" . $headerIconName);
        Storage::cloud()->delete("images/profileIcon/" . $profileIconName);
    }

    public function test_cannot_upload_without_jpg_png()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.gif');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.svg');

        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => $profile_icon_image,
            "changeHeaderIcon" => 1,
            "changeProfileIcon" => 1
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);
        $response->assertStatus(422);

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertSame($user->profile->only(['username', 'introduction']), $profile->only(['username', 'introduction']));

        $this->assertSame("http://localhost/img/profile/default_profile.png", $profile->profile_icon);


        $this->assertSame("http://localhost/img/profile/default_header.png", $profile->header_icon);
    }

    public function test_update_all_attributes()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.jpg');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.jpg');

        $expectHeaderIcon = $user->profile->header_icon;
        $expectProfileIcon = $user->profile->profile_icon;
        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => $profile_icon_image,
            "changeHeaderIcon" => 1,
            "changeProfileIcon" => 1
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));
        $this->assertFalse($expectHeaderIcon === $user->refresh()->profile->header_icon);
        $this->assertFalse($expectProfileIcon === $user->refresh()->profile->profile_icon);
    }

    public function test_update_without_header_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.jpg');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.jpg');

        $expectHeaderIcon = $user->profile->header_icon;
        $expectProfileIcon = $user->profile->profile_icon;
        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => "",
            "profile_icon" => $profile_icon_image,
            "changeHeaderIcon" => 0,
            "changeProfileIcon" => 1
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));
        $this->assertSame($expectHeaderIcon, $user->refresh()->profile->header_icon);
        $this->assertFalse($expectProfileIcon === $user->refresh()->profile->profile_icon);
    }

    public function test_update_without_profile_icon()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.jpg');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.jpg');

        $expectHeaderIcon = $user->profile->header_icon;
        $expectProfileIcon = $user->profile->profile_icon;
        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => "",
            "changeHeaderIcon" => 1,
            "changeProfileIcon" => 0
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));
        $this->assertFalse($expectHeaderIcon === $user->refresh()->profile->header_icon);
        $this->assertSame($expectProfileIcon, $user->refresh()->profile->profile_icon);
    }

    public function test_update_without_icons()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.jpg');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.jpg');

        $expectHeaderIcon = $user->profile->header_icon;
        $expectProfileIcon = $user->profile->profile_icon;
        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => "",
            "profile_icon" => "",
            "changeHeaderIcon" => 0,
            "changeProfileIcon" => 0
        ];

        $response = $this->actingAs($user)->post(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));
        $this->assertSame($expectHeaderIcon, $user->refresh()->profile->header_icon);
        $this->assertSame($expectProfileIcon, $user->refresh()->profile->profile_icon);
    }
}
