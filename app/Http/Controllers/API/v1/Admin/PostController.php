<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Requests\User\PostsUpdateRequest;
use App\Post;
use App\Transformers\v1\PostTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PostsCreateRequest;
use Symfony\Component\Translation\Exception\NotFoundResourceException;


class PostController extends Controller
{
    use Helpers;

    public function postPost(PostsCreateRequest $request)
    {
        $post_input = $request->only(['title', 'content']);

        $post_input = array_merge($post_input, ['author_id' => $request->user()->id]);

        if ($post = Post::create($post_input))
            return $this->response->item($post, new PostTransformer());
    }

    public function putPost(PostsUpdateRequest $request, $post_id)
    {
        $post_input = $request->only(['title', 'content']);

//        $post = $request->user()->posts()->find($post_id);
        $post = Post::find($post_id);

        if (empty($post))
            return $this->response->errorNotFound('data not found.');

        if ($post->update($post_input))
            return $this->response->item($post, new PostTransformer());

        return $this->response->errorInternal();
    }

    public function deletePost(Request $request, $post_id)
    {
//        $post = $request->user()->posts()->find($post_id);
        $post = Post::find($post_id);

        if (empty($post))
            return $this->response->errorNotFound('data not found.');

        if ($post->delete())
            return $this->response->accepted();

        return $this->response->errorInternal();
    }
}
