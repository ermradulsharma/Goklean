<?php

namespace App\Transformers\Api;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class PopularProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'car_prices' => fractal($product->prices, new CarProductPriceTransformer())->toArray()['data']
        ];
    }
}
