<?php
namespace App\Transformers;

use App\Post;
use DateTime;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['author', 'comments'];

    /**
     * Turn this item object into a generic array
     *
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id'           => (int) $post->id,
            'title'        => $post->title,
            'content'      => $post->content,
            'created_at'   => $post->created_at,
            'time_ago'     => time_ago($post->created_at),
            'url'          => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('posts.show', $post->id)
        ];
    }

    public function includeAuthor(Post $post)
    {
        if (!empty($post->author))
            return $this->item($post->author, new AdminTransformer());
        else
            return $this->null();
    }

    public function includeComments(Post $post)
    {
        if (count($post->comments) > 0)
            return $this->collection($post->comments, new CommentTransformer());
    }

}