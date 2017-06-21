<?php
/**
 * Created by PhpStorm.
 * User: Maras
 * Date: 2017/6/21
 * Time: 下午3:58
 */

namespace App\Transformers\v1;


use App\Post;
use League\Fractal\TransformerAbstract;

class AuthorPostTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id' => (int)$post->id,
            'title' => $post->title,
            'content' => $post->content,
            'created_at' => $post->created_at,
            'time_ago' => time_ago($post->created_at),
            'url' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('posts.show', $post->id)
        ];
    }
}