<?php

namespace App\Transformers\Api;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id'            => (int) $product->id,
            'name'          => (string) $product->name,
            'code'         => (string) $product->code,
            'description'       => (string) $product->description,
            'is_popular'       => (boolean) $product->is_popular,
            'product_group'       => (string) $product->product_group,
            'least_price'   => (int) $product->prices()->min('price')
        ];
    }
}