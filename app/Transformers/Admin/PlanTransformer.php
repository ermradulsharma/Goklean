<?php

namespace App\Transformers\Admin;

use App\Models\Plan;
use League\Fractal\TransformerAbstract;

class PlanTransformer extends TransformerAbstract
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
            'id' => $plan->id,
            'code' => $plan->code,
            'name' => $plan->name,
            'type' => ucfirst($plan->wash_qty_per),
            'qty' => $plan->wash_qty.' Washes',
            'popular' => $plan->is_popular ? 'Yes': 'No',
            'status' => $plan->is_active,
        ];
    }
}
