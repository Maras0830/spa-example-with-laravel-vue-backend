<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Claims\Custom;
use Tymon\JWTAuth\Facades\JWTAuth;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $auth
     * @return mixed
     */
    public function handle($request, Closure $next, string $auth)
    {
        try {
            JWTAuth::setRequest($request);

            if (! $user = JWTAuth::toUser(JWTAuth::getToken())) {
                return response()->json(['user_not_found'], 404);
            }

            $claims = JWTAuth::getPayload(JWTAuth::getToken())->toArray();

            if ($claims['type'] !== $auth) {
                return response()->json([ $auth . '_not_found'], 404);
            }

            $request->setUserResolver(function () use ($user) {
                return $user;
            });

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return $next($request);
    }
}
