<?php

namespace App\Http\Transformers;

use App\Models\BasePrice;
use App\Models\Plan;
use League\Fractal\TransformerAbstract;

class PlanTransformer extends TransformerAbstract
{
    public function transform(Plan $plan)
    {
        $wash_qty = $plan->wash_qty_per == "monthly" ? $plan->wash_qty : (int) $plan->wash_qty * 4;

        $lease_price = BasePrice::where('wash_qty', $wash_qty)->min('price');

        return [
            'id'            => (int) $plan->id,
            'name'          => (string) $plan->name,
            'code'          => (string) $plan->code,
            'description'          => (string) $plan->description,
            'razorpay_id'          => (string) $plan->razorpay_id,
            'wash_qty_per'       => (string) $plan->wash_qty_per,
            'wash_qty'       => (int) $plan->wash_qty,
            'product_group'       => (string) $plan->product_group,
            'is_popular'       => (boolean) $plan->is_popular,
            'least_price'   => (int) $lease_price
        ];
    }
}