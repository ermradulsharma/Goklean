<?php

namespace App\Transformers\Admin;

use App\Models\CustomerCar;
use League\Fractal\TransformerAbstract;

class CustomerCarTransformer extends TransformerAbstract
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
            'name' => ucwords(strtolower($customerCar->car->full_name)),
            'customer' => $customerCar->customer ? $customerCar->customer->first_name . ' ' . $customerCar->customer->last_name : null,
            'number' => $customerCar->number_plate,
            'color' => $customerCar->color,
            'status' => $customerCar->is_active
        ];
    }
}
