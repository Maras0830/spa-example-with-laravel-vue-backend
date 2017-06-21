<?php
namespace App\Transformers\v1;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user): array
    {
        return [
            'id'           => (int) $user->id,
            'name'         => (string) $user->name,
            'email'        => (string) $user->email
        ];
    }

}