<?php

namespace App\Transformers\Api;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductOrderTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id' => (int)$product->id,
            'code' => (string)$product->code,
            'name' => (string)$product->name,
            'description' => (string)$product->description,
            'is_popular' => (bool)$product->is_popular,
            "price" => $this->getPrice($product->prices->first()),
            "qty" => (int) 1,
            'type' => (string) $product->product_type,
            "has_discount" => (boolean) $product->discounts->count() > 0,
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
