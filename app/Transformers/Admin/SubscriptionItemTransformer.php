<?php

namespace App\Transformers\Admin;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class SubscriptionItemTransformer extends TransformerAbstract
{
    private $qty;

    /**
     * SubscriptionItemTransformer constructor.
     * @param $qty
     */
    public function __construct($qty)
    {
        $this->qty = $qty;
    }

    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            "id" => $product->id,
            "name" => $product->name,
            "code" => $product->code,
            "product_type" => ucfirst($product->product_type),
            "qty" => 1,
            "selected" => false
        ];
    }
}
