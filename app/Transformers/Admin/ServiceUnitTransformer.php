<?php

namespace App\Transformers\Admin;

use App\Models\ServiceUnit;
use League\Fractal\TransformerAbstract;

class ServiceUnitTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param ServiceUnit $unit
     * @return array
     */
    public function transform(ServiceUnit $unit)
    {
        return [
            'id' => $unit->id,
            'code' => $unit->unique_code,
            'name' => $unit->full_name,
            'city' => $unit->city->name,
            'email' => $unit->email,
            'mobile' => $unit->mobile,
            'status' => $unit->is_active,
        ];
    }
}
