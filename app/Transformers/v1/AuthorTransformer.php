<?php
/**
 * Created by PhpStorm.
 * User: Maras
 * Date: 2017/6/21
 * Time: 下午3:56
 */

namespace App\Transformers\v1;


use App\Admin;
use League\Fractal\TransformerAbstract;

class AuthorTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['posts', 'comments'];

    /**
     * Turn this item object into a generic array
     *
     * @param Admin $admin
     * @return array
     */
    public function transform(Admin $admin): array
    {
        return [
            'id'           => (int) $admin->id,
            'name'         => (string) $admin->name,
            'email'        => (string) $admin->email
        ];
    }

    public function includePosts(Admin $admin)
    {
        if (!empty($admin->posts))
            return $this->collection($admin->posts, new AuthorPostTransformer());
        else
            return [];
    }

    public function includeComments(Admin $admin)
    {
        if (!empty($admin->comments))
            return $this->collection($admin->comments, new AuthorCommentTransformer());
        else
            return [];
    }
}