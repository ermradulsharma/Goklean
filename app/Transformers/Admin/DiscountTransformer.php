<?php

namespace App\Transformers\Admin;

use App\Models\Discount;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class DiscountTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Discount $discount
     * @return array
     */
    public function transform(Discount $discount)
    {
        return [
            'id' => $discount->id,
            'name' => $discount->name,
            'item' => $discount->item->name,
            'type' => ucfirst($discount->discount_type),
            'value' => $discount->formatted_discount,
            'method' => ucfirst($discount->discount_method),
            'valid_from' => Carbon::parse($discount->valid_from)->toFormattedDateString(),
            'valid_until' => Carbon::parse($discount->valid_until)->toFormattedDateString(),
            'max_discount_value' => $discount->max_discount_value,
            'max_cart_amount' => $discount->max_cart_amount,
            'status' => $discount->is_active,
            'access_to' => $discount->is_private ? $discount->userGroup ? $discount->userGroup->name : '' : 'Public'
        ];
    }
}
