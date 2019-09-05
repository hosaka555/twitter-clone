<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;

class AuthUserProvider extends EloquentUserProvider
{
    public function retrieveById($identifier)
    {
        $result = $this->createModel()->newQuery()
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->select(['users.*', 'profiles.profile_icon', "profiles.username"])
            ->find($identifier);
        $result->profile_icon = $this->profile_icon ?? asset('img/profile/default_profile.png');
        return $result;
    }
}
