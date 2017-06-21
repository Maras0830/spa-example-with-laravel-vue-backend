<?php
/**
 * Created by PhpStorm.
 * User: Maras
 * Date: 2017/6/21
 * Time: 下午4:00
 */

namespace App\Transformers\v1;


use App\Comment;
use League\Fractal\TransformerAbstract;

class AuthorCommentTransformer extends TransformerAbstract
{
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
}