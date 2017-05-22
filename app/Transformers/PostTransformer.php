<?php
namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @param Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id'           => (int) $post->id,
            'title'        => $post->title,
            'content'      => $post->content,
            'created_at'   => $post->created_at
        ];
    }

}