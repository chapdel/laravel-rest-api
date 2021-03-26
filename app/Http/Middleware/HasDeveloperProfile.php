<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasDeveloperProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user->developer) {
            abort(412, "unauthorized action");
        }
        return $next($request);
    }
}
