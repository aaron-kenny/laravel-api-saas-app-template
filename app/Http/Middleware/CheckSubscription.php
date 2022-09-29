<?php

namespace App\Http\Middleware;

use Closure;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ! $request->user()->subscribed('product_two')) {
            if($request->expectsJson()){
                return response()->json([
                    'message' => 'The user does not have an active subscription to Product Two'
                ], 403);
            } else {
                return redirect(config('app.subscribe_url'));
            }
        }
        
        return $next($request);
    }
}
