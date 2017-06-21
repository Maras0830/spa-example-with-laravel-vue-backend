<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Requests\User\CommentsCreateRequest;
use App\Http\Requests\User\CommentsUpdateRequest;
use App\Transformers\v1\CommentTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    use Helpers;

    public function postSubComments(CommentsCreateRequest $request, $post_id, $comment_id)
    {
        $user = $request->user();

        $comment_input = $request->only('title', 'content');

        $post = $this->api->get('api/posts/' . $post_id);

        $comment_input = array_merge($comment_input,['post_id' => $post_id, 'comment_from_id' => $user->id, 'comment_from_type' => 'App\\Admin']);

        if (!$post)
            return $this->response->errorNotFound('Post not found');

        $comment = $post->all_comments()->where('id', $comment_id)->first();

        if (!$comment)
            return $this->response->errorNotFound('Comment not found');

        if ($comment = $comment->comments()->create($comment_input))
            return $this->response->item($comment, new CommentTransformer());

        return $this->response->item($comment, new CommentTransformer());
    }

    public function putComments(CommentsUpdateRequest $request, $post_id, $comment_id)
    {
        $user = $request->user();

        $comment_input = $request->only('title', 'content');

        $post = $this->api->get('api/posts/' . $post_id);

        if (!$post)
            return $this->response->errorNotFound('Post not found');

        $comment = $post->all_comments()->where('id', $comment_id)->where('comment_from_id', $user->id)->where('comment_from_type', 'App\\Admin')->first();

        if (!$comment)
            return $this->response->errorNotFound('Comment not found');

        if ($comment->update($comment_input))
            return $this->response->item($comment, new CommentTransformer());

        return $this->response->errorInternal();
    }

    public function postComments(CommentsCreateRequest $request, $post_id)
    {
        $user = $request->user();

        $comment_input = $request->only('title', 'content');

        $post = $this->api->get('api/posts/' . $post_id);

        if (!$post)
            return $this->response->errorNotFound('Post not found');

        $comment_input = array_merge($comment_input, ['post_id' => $post_id]);

        if ($comment = $user->comment_from()->create($comment_input))
            return $this->response->item($comment, new CommentTransformer());

        return $this->response->errorInternal();
    }

    public function deleteComments(Request $request, $post_id, $comment_id)
    {
        $user = $request->user();

        $post = $this->api->get('api/posts/' . $post_id);

        if (!$post)
            return $this->response->errorNotFound('Post not found');

        $comment = $post->all_comments()->where('id', $comment_id)->where('comment_from_id', $user->id)->where('comment_from_type', 'App\\Admin')->first();

        if (!$comment)
            return $this->response->errorNotFound('Comment not found');

        if ($comment->delete())
            return $this->response->accepted();

        return $this->response->errorInternal();
    }
}
