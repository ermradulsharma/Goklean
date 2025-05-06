<?php

namespace App\Transformers\Admin;

use App\Models\CustomerCar;
use League\Fractal\TransformerAbstract;

class CustomerCarSearchTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param CustomerCar $customerCar
     * @return array
     */
    public function transform(CustomerCar $customerCar)
    {
        return [
            'id' => $customerCar->id,
            'name' => ucwords(strtolower("{$customerCar->car->full_name} ({$customerCar->number_plate})"))
        ];
    }
}
