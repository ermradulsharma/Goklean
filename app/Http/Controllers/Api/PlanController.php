<?php

namespace App\Http\Controllers\Api;


use App\Http\Transformers\Api\PopularPlanTransformer;
use App\Http\Transformers\BasePriceTransformer;
use App\Http\Transformers\PlanTransformer;
use App\Models\BasePrice;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController
{
    public function popularPlans()
    {
        $plans = Plan::where('is_popular', 1)->orderBy('sort_order')->get();
        $basePrices = BasePrice::with('carType')->get();
        $prices = fractal($plans, new PopularPlanTransformer($basePrices))->toArray();
        return response()->json($prices['data'], 200);
    }
}
