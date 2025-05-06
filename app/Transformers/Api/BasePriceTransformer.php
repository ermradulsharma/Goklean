<?php

namespace App\Http\Transformers;

use App\Models\BasePrice;
use League\Fractal\TransformerAbstract;

class BasePriceTransformer extends TransformerAbstract
{
    public function transform(BasePrice $basePrice)
    {
        return [
            'car_type_id'            => (int) $basePrice->car_type_id,
            'car_type_name'            => (string) $basePrice->carType->name,
            'image_path'            => (string) asset($basePrice->carType->image_path),
            'wash_qty'            => (int) $basePrice->wash_qty,
            'price'            => (int) $basePrice->price
        ];
    }
}