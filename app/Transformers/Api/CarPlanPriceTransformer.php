<?php

namespace App\Http\Transformers\Api;

use App\Models\BasePrice;
use League\Fractal\TransformerAbstract;

class CarPlanPriceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param BasePrice $basePrice
     * @return array
     */
    public function transform(BasePrice $basePrice)
    {
        return [
            'id'            => (int) $basePrice->car_type_id,
            'name'            => (string) $basePrice->carType->name,
            'image_path'            => (string) asset($basePrice->carType->image_path),
            'wash_qty'            => (int) $basePrice->wash_qty,
            'price'            => (int) $basePrice->price
        ];
    }
}
