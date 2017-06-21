<?php

namespace App\Http\Controllers\API\v1\User;

use App\Transformers\v1\UserTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use Helpers;

    public function getMe(Request $request)
    {
        return $this->response->item($request->user(), new UserTransformer);
    }
}
