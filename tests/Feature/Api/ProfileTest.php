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
        $this->assertSame(Profile::where('user_id', $general_user->id)->get()->first()->toJson(), $response->original);
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
            "profile_icon" => $profile_icon_image
        ];

        $response = $this->actingAs($user)->put(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(204);
        $this->assertSame(array_slice($update_profile, 0, 2), $profile->only(['username', 'introduction']));
        $this->assertTrue(Storage::disk('minio')->exists($profile->header_icon));
        $this->assertTrue(Storage::disk('minio')->exists($profile->profile_icon));
    }

    public function test_cannot_upload_without_jpg_png()
    {
        $user = tap(factory(User::class)->create(), function ($user) {
            $profile = factory(Profile::class)->make();
            $user->profile()->save($profile);
        });

        Storage::fake();

        $header_icon_image = UploadedFile::fake()->image('header_icon.gif');
        $profile_icon_image = UploadedFile::fake()->image('profile_icon.pdf');

        $update_profile = [
            "username" => "updated username",
            "introduction" => "Updated introduction",
            "header_icon" => $header_icon_image,
            "profile_icon" => $profile_icon_image
        ];

        $response = $this->actingAs($user)->put(route("api.update_profile", ["account_id" => $user->account_id]), $update_profile);

        $profile = Profile::where('user_id', $user->id)->first();
        $response->assertStatus(422);
        $this->assertSame($user->profile->only(['username','introduction']), $profile->only(['username', 'introduction']));

        $this->assertFalse(Storage::disk('minio')->exists($profile->header_icon));
        $this->assertFalse(Storage::disk('minio')->exists($profile->profile_icon));
    }

}
