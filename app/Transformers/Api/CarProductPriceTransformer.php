<?php

namespace App\Http\Transformers\Api;

use App\Models\CarType;
use League\Fractal\TransformerAbstract;

class CarProductPriceTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param CarType $carType
     * @return array
     */
    public function transform(CarType $carType)
    {
        return [
            'id' => $carType->id,
            'name' => $carType->name,
            'image_path' => asset($carType->image_path),
            'wash_qty' => 1,
            'price' => $carType->pivot->price,
        ];
    }
}
