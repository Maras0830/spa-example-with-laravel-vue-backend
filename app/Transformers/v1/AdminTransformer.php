<?php
namespace App\Transformers\v1;

use App\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
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
}