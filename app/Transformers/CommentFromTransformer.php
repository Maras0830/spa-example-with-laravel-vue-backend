<?php
namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class CommentFromTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param $comment_from
     * @return array
     */
    public function transform($comment_from): array
    {
        return [
            'id'           => (int) $comment_from->id,
            'name'         => (string) $comment_from->name,
            'email'        => (string) $comment_from->email,
            'type'         => (string) $comment_from instanceof User ? 'User' : 'Admin'
        ];
    }
}