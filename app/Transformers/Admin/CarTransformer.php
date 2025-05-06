<?php

namespace App\Transformers\Admin;

use App\Models\Car;
use League\Fractal\TransformerAbstract;

class CarTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Car $car
     * @return array
     */
    public function transform(Car $car)
    {
        return [
            'id' => $car->id,
            'name' => "{$car->brand->name} {$car->name}",
            'image_path' => $car->image_path,
            'type' => $car->type->name,
            'status' => $car->is_active
        ];
    }
}
