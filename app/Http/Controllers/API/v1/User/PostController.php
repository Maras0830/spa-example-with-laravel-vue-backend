<?php

namespace App\Http\Controllers\API\v1\User;

use App\Post;
use App\Transformers\PostTransformer;
use Dingo\Api\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use Helpers;

    public function getPosts()
    {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(3);

        return $this->response->paginator($posts, new PostTransformer());
    }

    public function getPost($id)
    {
        $post = Post::find($id);

        if (!$post)
            return $this->response->errorNotFound('Post not found');

        return $this->response->item($post, new PostTransformer());
    }
}
