<?php

namespace App\Http\Transformers;

use App\Models\Car;
use League\Fractal\TransformerAbstract;

class SearchCarTransformer extends TransformerAbstract
{
    public function transform(Car $car)
    {
        return [
            'id'            => (int) $car->id,
            'name'          => (string) $car->full_name,
            'car_type'         => (string) $car->type->name,
            'image_path'       => (string) asset($car->image_path)
        ];
    }
}
