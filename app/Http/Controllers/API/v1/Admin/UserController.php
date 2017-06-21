<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Transformers\v1\AdminTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    use Helpers;

    public function getMe(Request $request)
    {
        return $this->response->item($request->user(), new AdminTransformer());
    }
}
