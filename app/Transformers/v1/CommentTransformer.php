<?php
namespace App\Transformers\v1;

use App\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['author', 'sub_comments'];
    /**
     * Turn this item object into a generic array
     *
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array
    {
        return [
            'id'       => (int) $comment->id,
            'title'    => (string) $comment->title,
            'content'  => (string) $comment->content,
            'time_ago'     => time_ago($comment->created_at)
        ];
    }

    public function includeAuthor(Comment $comment)
    {
        if (!empty($comment->comment_from))
            return $this->item($comment->comment_from, new CommentFromTransformer());
        else
            return $this->null();
    }

    public function includeSubComments(Comment $comment)
    {
        return $this->collection($comment->comments, new CommentTransformer());
    }
}