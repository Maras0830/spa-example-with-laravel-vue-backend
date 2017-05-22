<?php

namespace App\Http\Controllers\API\v1;

use App\Post;
use App\Transformers\PostTransformer;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use Helpers;

    public function getPosts()
    {
        $posts = Post::paginate(10);

        return $this->response->paginator($posts, new PostTransformer());
    }
}
