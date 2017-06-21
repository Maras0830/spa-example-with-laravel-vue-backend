<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class GuardAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $auth
     * @return mixed
     */
    public function handle($request, Closure $next, string $auth = '')
    {
        if (!empty($auth)) {
            Config::set('auth.defaults.guard', $auth);
            Config::set('jwt.user', 'App\\' . ucfirst($auth));
        }

        return $next($request);
    }
}
