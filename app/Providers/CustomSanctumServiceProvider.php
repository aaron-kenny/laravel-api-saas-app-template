<?php

namespace App\Providers;

use Laravel\Sanctum\SanctumServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\Auth\CustomSanctumGuard;

class CustomSanctumServiceProvider extends SanctumServiceProvider
{
    /**
     * Configure the Sanctum authentication guard.
     *
     * @return void
     */
    protected function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->viaRequest('sanctum', new CustomSanctumGuard($auth, config('sanctum.expiration')));
        });
    }
}
