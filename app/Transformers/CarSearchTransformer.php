<?php

namespace App\Transformers;

use App\Models\Car;
use League\Fractal\TransformerAbstract;

class CarSearchTransformer extends TransformerAbstract
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
        ];
    }
}
