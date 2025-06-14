<?php

namespace App\Transformers\Api;

use App\Models\CustomerCar;
use League\Fractal\TransformerAbstract;

class CarSubscriptionTransformer extends TransformerAbstract
{
    public function transform(CustomerCar $car)
    {
        return [
            'car_id'            => (int) $car->id,
            'subscription_id'            => (int) $car->subscriptions[0]->id,
            'plan_name'   => $car->subscriptions[0]->plan->name,
            'car_name'          => (string) strtoupper($car->car->brand->name." ".$car->car->name),
            'car_type'         => (string) $car->car->car_type,
            'image_path'       => (string) asset('storage/cars/'.$car->car->image_path),
            'color'       => (string) $car->color,
            'registration_number'       => (string) $car->registration_number,
            'subscription_status'   => ucfirst($car->subscriptions[0]->razorpay_status),
        ];
    }
}