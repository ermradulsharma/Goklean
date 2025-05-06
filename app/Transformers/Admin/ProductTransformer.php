<?php

namespace App\Transformers\Admin;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
            'type' => ucfirst($product->product_type),
            'popular' => $product->is_popular ? 'Yes': 'No',
            'status' => $product->is_active,
        ];
    }
}
