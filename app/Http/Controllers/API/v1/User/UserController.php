<?php

namespace App\Http\Controllers\API\v1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getMe(Request $request)
    {
        $user = $request->input('auth');

        return response()->json(['data' => $user, 'status_code' => 200, 'message' => 'successful.'], 200);
    }
}
