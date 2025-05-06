<?php

namespace App\Transformers\Admin;

use App\Models\CarBrand;
use League\Fractal\TransformerAbstract;

class CarBrandTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param CarBrand $brand
     * @return array
     */
    public function transform(CarBrand $brand)
    {
        return [
            'id' => $brand->id,
            'name' => $brand->name,
            'logo_path' => $brand->logo_path,
            'status' => $brand->is_active,
        ];
    }
}
