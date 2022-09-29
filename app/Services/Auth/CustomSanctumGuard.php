<?php

namespace App\Services\Auth;

use Laravel\Sanctum\Guard;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class CustomSanctumGuard extends Guard
{
    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    // public function __invoke(Request $request)
    // {
    //     if ($user = $this->auth->guard(config('sanctum.guard', 'web'))->user()) {
    //         return $this->supportsTokens($user)
    //                     ? $user->withAccessToken(new TransientToken)
    //                     : $user;
    //     }

    //     if ($token = $request->bearerToken() ?? $token = $request->input('productTwoApiToken')) {
    //         $model = Sanctum::$personalAccessTokenModel;

    //         $accessToken = $model::where('token', hash('sha256', $token))->first();

    //         if (! $accessToken ||
    //             ($this->expiration &&
    //              $accessToken->created_at->lte(now()->subMinutes($this->expiration)))) {
    //             return;
    //         }

    //         return $this->supportsTokens($accessToken->tokenable) ? $accessToken->tokenable->withAccessToken(
    //             tap($accessToken->forceFill(['last_used_at' => now()]))->save()
    //         ) : null;
    //     }
    // }


    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        foreach (Arr::wrap(config('sanctum.guard', 'web')) as $guard) {
            if ($user = $this->auth->guard($guard)->user()) {
                return $this->supportsTokens($user)
                    ? $user->withAccessToken(new TransientToken)
                    : $user;
            }
        }

        if ($token = $request->bearerToken() ?? $token = $request->input('productTwoApiToken')) {
            $model = Sanctum::$personalAccessTokenModel;

            $accessToken = $model::findToken($token);

            if (! $accessToken ||
                ($this->expiration &&
                 $accessToken->created_at->lte(now()->subMinutes($this->expiration))) ||
                ! $this->hasValidProvider($accessToken->tokenable)) {
                return;
            }

            return $this->supportsTokens($accessToken->tokenable) ? $accessToken->tokenable->withAccessToken(
                tap($accessToken->forceFill(['last_used_at' => now()]))->save()
            ) : null;
        }
    }

}
