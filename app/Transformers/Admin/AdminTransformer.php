<?php

namespace App\Transformers\Admin;

use App\Models\Admin;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Admin $admin
     * @return array
     */
    public function transform(Admin $admin)
    {
        return [
            'id' => $admin->id,
            'code' => $admin->unique_code,
            'name' => $admin->full_name,
            'city' => $admin->city->name,
            'email' => $admin->email,
            'mobile' => $admin->mobile,
            'status' => $admin->is_active,
        ];
    }
}
