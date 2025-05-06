<?php

namespace App\Http\Transformers;

use App\Models\ProductPrice;
use League\Fractal\TransformerAbstract;

class ProductPriceTransformer extends TransformerAbstract
{
    public function transform(ProductPrice $price)
    {
        return [
            'car_type_id'            => (int) $price->car_type_id,
            'car_type_name'            => (string) $price->carType->name,
            'image_path'            => (string) asset($price->carType->image_path),
            'price'            => (int) $price->price
        ];
    }
}