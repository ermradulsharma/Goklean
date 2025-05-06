<?php

namespace App\Transformers;

use App\Models\CarColor;
use League\Fractal\TransformerAbstract;

class ColorSearchTransformer extends TransformerAbstract
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
            'id' => $color->name,
            'name' => $color->name
        ];
    }
}
