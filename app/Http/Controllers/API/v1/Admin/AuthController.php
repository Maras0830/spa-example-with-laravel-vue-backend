<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials, ['type' => 'admin'])) {
                return response()->json(['error' => 'invalid_credentials', 'status_code' => 401], 200);
            }

            $claims = JWTAuth::getPayload($token)->getClaims();

            $token_ttl = array_values(array_filter($claims, function($claim){
                return $claim instanceof Expiration;
            }))[0]->getValue();

            $admin = JWTAuth::toUser($token)->toArray();
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token', 'token_ttl', 'admin'));
    }
}
