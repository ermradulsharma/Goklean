<?php

namespace App\Transformers\Api;

use App\Models\Plan;
use League\Fractal\TransformerAbstract;

class SubscriptionPlanTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Plan $plan
     * @return array
     */
    public function transform(Plan $plan)
    {
        return [
            'id' => (int) $plan->id,
            'name' => (string) $plan->name,
            'code' => (string) $plan->code,
            'description' => (string) $plan->description,
            'qty' =>  (int) 1,
            'wash_qty_per' =>  (string) $plan->wash_qty_per,
            'wash_qty' =>  (int) $plan->wash_qty,
            'total_wash_qty' =>  (int) $plan->prices->wash_qty,
            'price' => (int) $plan->prices->price,
            'is_popular' => (bool) $plan->is_popular,
            "has_discount" => (boolean) $plan->discounts->count() > 0,
            "discounted_price" => $this->getDiscountedPrice($plan->discounts->first(), $plan->prices->price),
        ];
    }

    public function getDiscountedPrice($discount, $price)
    {
        if($discount) {
            return $discount['discount_type'] == 'fixed' ?
                $price - $discount['discount_value'] :
                $price - ($price * $discount['discount_value'] / 100);
        }
        return 0;
    }
}
