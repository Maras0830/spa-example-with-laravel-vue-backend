<?php

namespace App\Http\Controllers\API\v1\User;

use App\Admin;
use App\Transformers\v1\AdminTransformer;
use App\Transformers\v1\AuthorTransformer;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    use Helpers;

    public function getAuthors()
    {
        $authors = Admin::orderBy('created_at', 'DESC')->paginate(3);

        return $this->response->paginator($authors, new AuthorTransformer());
    }

    public function getAuthor($id)
    {
        $author = Admin::find($id);

        return $this->response->item($author, new AuthorTransformer());
    }
}
