<?php

namespace App\Transformers\Admin;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class InvoiceProductTransformer extends TransformerAbstract
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
            "id" => $product->id,
            "name" => $product->name,
            "code" => $product->code,
            "product_type" => ucfirst($product->product_type),
            "price" => $this->getPrice($product->prices->first()),
            "qty" => 1,
            "has_discount" => $product->discounts->count() > 0,
            "discounted_price" => $this->getDiscountedPrice($product->discounts->first(), $product->prices->first()),
        ];
    }

    public function getPrice($price)
    {
        if($price) {
            return $price->pivot->price;
        }
        return 0;
    }

    public function getDiscountedPrice($discount, $price)
    {
        if($discount) {
            return $discount['discount_type'] == 'fixed' ?
                $price->pivot->price - $discount['discount_value'] :
                $price->pivot->price - ($price->pivot->price * $discount['discount_value'] / 100);
        }
        return 0;
    }
}
