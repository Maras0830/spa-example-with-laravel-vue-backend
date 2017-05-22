<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function getMe(Request $request)
    {
        $admin = $request->input('auth');

        return response()->json(['data' => $admin, 'status_code' => 200, 'message' => 'successful.'], 200);
    }
}
