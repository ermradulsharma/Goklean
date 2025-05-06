<?php


namespace App\Repositories;

use App\Models\BasePrice;
use App\Models\Plan;
use App\Models\Product;
use App\Transformers\Admin\InvoiceProductTransformer;
use App\Transformers\Api\ProductOrderTransformer;
use App\Transformers\Api\SubscriptionPlanTransformer;

class PricingRepository
{
    /**
     * Calculate and fetch product prices for a car type
     *
     * @param $carTypeId
     * @param null $userGroup
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchProductsWithPricing($carTypeId, $userGroup = null)
    {
        $products = Product::with(['prices' => function($query) use($carTypeId) {
            $query->where('car_type_id', '=', $carTypeId);
        }, 'discounts' => function($query) use($userGroup) {
            $query->where('user_group_id', '=', $userGroup)->where('is_active', 1);
        }])->active()->get();

        return fractal($products, new InvoiceProductTransformer())->toArray()['data'];
    }

    /**
     * Calculate and fetch plan & addon prices for a car type
     *
     * @param $plan
     * @param $addons
     * @param $carTypeId
     * @param null $userGroup
     * @return array
     */
    public function fetchSubscriptionProductsWithPricing($plan, $addons, $carTypeId, $userGroup = null)
    {
        $products = [];
        $qty = $plan->wash_qty_per == 'monthly' ? $plan->wash_qty : $plan->wash_qty * 4;

        $basePrice = BasePrice::where('car_type_id', '=', $carTypeId)->where('wash_qty', '=', $qty)->first();

        // Fetch single wash product GK-EXT-1W
        $product = Product::where('code', '=', 'GK-EXT-1W')->first();

        //calculate discount if any
        $hasDiscount = $plan->discounts->count() > 0;
        $discountedPrice = 0;
        $discount = $plan->discounts->first();
        if($discount) {
            $discountedPrice =  $discount['discount_type'] == 'fixed' ?
                ($basePrice->price/$qty) - $discount['discount_value'] :
                ($basePrice->price/$qty) - (($basePrice->price/$qty) * $discount['discount_value'] / 100);
        }

        array_push($products, [
            "id" => $product->id,
            "name" => $product->name,
            "code" => $product->code,
            "product_type" => ucfirst($product->product_type),
            "price" => $basePrice->price/$qty,
            "qty" => $qty,
            "has_discount" => $hasDiscount,
            "discounted_price" => $discountedPrice,
        ]);

        // get addon pricing
        $addonItems = Product::with(['prices' => function($query) use($carTypeId) {
            $query->where('car_type_id', '=', $carTypeId);
        }, 'discounts' => function($query) use($userGroup) {
            $query->where('user_group_id', '=', $userGroup);
        }])->whereIn('id', $addons->pluck('id'))->active()->get();

        $transformed = fractal($addonItems, new InvoiceProductTransformer())->toArray()['data'];

        foreach ($transformed as $product) {
            $addon = $addons->where('code', '=', $product['code'])->first();
            $qty = $addon ? $addon->pivot->qty : $addon['qty'];
            $product['qty'] = $qty;
            array_push($products, $product);
        }

        return $products;
    }

    /**
     * Get product prices for mobile app api
     *
     * @param $carTypeId
     * @param null $userGroup
     * @param $productType
     * @return array
     */
    public function getProductPrices($carTypeId, $productType, $userGroup = null)
    {
        if($productType != null) {
            $products = Product::with(['prices' => function($query) use($carTypeId) {
                $query->where('car_type_id', '=', $carTypeId);
            }, 'discounts' => function($query) use($userGroup) {
                $query->where('user_group_id', '=', $userGroup)->where('is_active', 1);
            }])->where('product_type', $productType)->active()->get();
        } else {
            $products = Product::with(['prices' => function($query) use($carTypeId) {
                $query->where('car_type_id', '=', $carTypeId);
            }, 'discounts' => function($query) use($userGroup) {
                $query->where('user_group_id', '=', $userGroup)->where('is_active', 1);
            }])->active()->get();
        }

        return fractal($products, new ProductOrderTransformer())->toArray();
    }

    /**
     * Get plan prices for mobile app api
     *
     * @param $carTypeId
     * @param null $userGroup
     * @return array
     */
    public function getPlansWithPrices($carTypeId, $userGroup = null)
    {
        $plans =  Plan::active()->with(['discounts' => function($query) use($userGroup) {
            $query->where('user_group_id', '=', $userGroup)->where('is_active', 1);
        }])->orderBy('sort_order', 'asc')->get();

        $plans->transform(function ($item) use ($carTypeId) {

            $wash_qty = $item->wash_qty_per == "monthly" ? $item->wash_qty : (int) $item->wash_qty * 4;

            $item->prices = BasePrice::with('carType')
                ->where('car_type_id', $carTypeId)
                ->where('wash_qty', $wash_qty)
                ->first();

            return $item;
        });

        return fractal($plans, new SubscriptionPlanTransformer())->toArray();
    }

    public function fetchEstimateProducts($products, $carTypeId, $userGroup = null)
    {
        $products = Product::with(['prices' => function($query) use($carTypeId) {
            $query->where('car_type_id', '=', $carTypeId);
        }, 'discounts' => function($query) use($userGroup) {
            $query->where('user_group_id', '=', $userGroup)->where('is_active', 1);
        }])->whereIn('id', $products)->active()->get();

        return fractal($products, new ProductOrderTransformer())->toArray();
    }

    public function getGeneralTerms()
    {
        return [
            "Booking dates are subject to availability. If any change you'll receive a call from one of our executives.",
            "Maximum 1 Reschedule will be accepted per wash. If more than booking will be treated as cancelled."
        ];
    }

    public function getCancellationTerms()
    {
        return [
            "You can cancel a scheduled wash up to 12 hours prior to the scheduled time. In that case, refund will be provided. See below for the refund details",
            "In all cases, 10% of processing fee will be applicable.",
            "No refund will be provided if maximum reschedules exceed the acceptable limit.",
        ];
    }
}
