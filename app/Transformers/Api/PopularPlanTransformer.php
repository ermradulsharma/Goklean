<?php

namespace App\Http\Transformers\Api;

use App\Models\Plan;
use League\Fractal\TransformerAbstract;

class PopularPlanTransformer extends TransformerAbstract
{
    private $basePrices;

    public function __construct($basePrices)
    {
        $this->basePrices = $basePrices;
    }

    /**
     * A Fractal transformer.
     *
     * @param Plan $plan
     * @return array
     */
    public function transform(Plan $plan)
    {
        $washQty = $plan->wash_qty_per == "monthly" ? $plan->wash_qty : (int) $plan->wash_qty * 4;
        return [
            'id' => $plan->id,
            'code' => $plan->code,
            'name' => $plan->name,
            'car_prices' => fractal(collect($this->basePrices)->where('wash_qty', $washQty)->all(), new CarPlanPriceTransformer())->toArray()['data']
        ];
    }
}
