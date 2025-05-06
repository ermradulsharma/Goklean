<?php

namespace App\Transformers\Api;

use App\Models\CustomerCar;
use League\Fractal\TransformerAbstract;

class CustomerCarTransformer extends TransformerAbstract
{
    public function transform(CustomerCar $customerCar)
    {
        return [
            'id'            => (int) $customerCar->id,
            'name'          => (string) $customerCar->car->full_name,
            'car_type'         => (string) $customerCar->car->type->name,
            'image_path'       => (string) asset($customerCar->car->image_path),
            'color'       => (string) $customerCar->color,
            'number_plate'       => (string) $customerCar->number_plate,
            'has_active_subscription' => (boolean) $customerCar->subscriptions()->where('status', 'active')->count() > 0
        ];
    }
}
