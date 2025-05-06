<?php

namespace App\Transformers\Admin;

use App\Models\CarColor;
use League\Fractal\TransformerAbstract;

class CarColorTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param CarColor $color
     * @return array
     */
    public function transform(CarColor $color)
    {
        return [
            'id' => $color->id,
            'name' => $color->name,
            'status' => $color->is_active,
        ];
    }
}
