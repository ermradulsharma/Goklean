<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BasePrice;
use App\Models\CustomerCar;
use App\Models\Plan;
use App\Repositories\PricingRepository;
use App\Transformers\Api\CustomerCarTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PricingController extends Controller
{
    /**
     * @var PricingRepository
     */
    private $repository;

    public function __construct(PricingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get product prices for a car
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarProducts($id, Request $request)
    {
        $customerCar = CustomerCar::with('car')->find($id);

        $productType = $request->has('type') ? $request->type : null;

        $products = $this->repository->getProductPrices($customerCar->car->car_type_id, $productType, $request->user()->user_group_id);

        return response()->json($products['data'], 200);
    }

    /**
     * Get plan prices for a car
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarPlans($id, Request $request)
    {
        $customerCar = CustomerCar::with('car')->find($id);

        $plans = $this->repository->getPlansWithPrices($customerCar->car->car_type_id, $request->user()->user_group_id);

        return response()->json($plans['data'], 200);
    }

    /**
     * Get estimate of the booking
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEstimate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_car_id' => 'required',
            'booking_type' => 'required',
            'plan_id' => 'required_if:booking_type,bulk',
            'product_id' => 'required_if:booking_type,single'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $customerCar = CustomerCar::with(['car', 'customer'])->findOrFail($request->customer_car_id);
        $wallet_balance = $request->user()->balanceFloat;
        $carType = $customerCar->car->car_type_id;
        $totalPrice = 0;
        $tax_amount = 0;
        $products = [];
        $order_items = [];
        $addons_qty = [];
        $totalWashes = 1;
        $refund_policy = "";
        $wash_policy = "";
        $allow_wallet = false;
        $wallet_deduction_amount = 0;
        if ($request->booking_type == 'bulk' && $request->plan_id != null) {
            $group = $customerCar->customer->user_group_id;
            $plan = Plan::with(['discounts' => function ($query) use ($group) {
                $query->where('user_group_id', '=', $group)->where('is_active', 1);
            }])->find($request->plan_id);
            $wash_qty = $plan->wash_qty_per == "monthly" ? (int) $plan->wash_qty : (int) $plan->wash_qty * 4;
            $totalWashes = $wash_qty;
            $planPrice = BasePrice::where('car_type_id', $customerCar->car->car_type_id)->where('wash_qty', $wash_qty)->first();
            $hasDiscount = $plan->discounts->count() > 0;
            $discountedPrice = 0;
            $discount = $plan->discounts->first();
            if ($discount) {
                $discountedPrice = $discount['discount_type'] == 'fixed' ? $planPrice->price - $discount['discount_value'] : $planPrice->price - ($planPrice->price * $discount['discount_value'] / 100);
            }
            array_push($order_items, [
                'id' => (int) $plan->id,
                'code' => (string) $plan->code,
                'name' => (string) $plan->name,
                'description' => (string) $plan->description,
                'is_popular' => (bool) $plan->is_popular,
                'qty' => (int) 1,
                'price' => $planPrice->price,
                'type' => (string) 'primary',
                'has_discount' => (bool) $hasDiscount,
                'discounted_price' => $discountedPrice
            ]);
            $totalPrice += $hasDiscount ? $discountedPrice : $planPrice->price;
        }
        if ($request->booking_type == 'single' && $request->product_id != null) {
            array_push($products, $request->product_id);
        }
        if ($request->addons != null) {
            foreach ($request->addons as $addon) {
                array_push($products, $addon['id']);
                $addons_qty[$addon['code']] = $addon['qty'];
            }
        }
        $estimateProducts = $this->repository->fetchEstimateProducts($products, $carType, $customerCar->customer->user_group_id);
        foreach ($estimateProducts['data'] as $key => $product) {
            if ($request->booking_type == 'bulk') {
                $product['qty'] = $addons_qty[$product['code']];
                $subTotal = $product['has_discount'] ? $product['qty'] * $product['discounted_price'] : $product['qty'] * $product['price'];
            } else {
                $subTotal = $product['has_discount'] ? $product['qty'] * $product['discounted_price'] : $product['qty'] * $product['price'];
            }
            $totalPrice += $subTotal;
            array_push($order_items, $product);
        }
        if ($wallet_balance !== 0) {
            $allow_wallet = true;
            $wallet_amount = (75 / 100) * $totalPrice;
            $wallet_deduction_amount = $wallet_balance > $wallet_amount ? $wallet_amount : $wallet_balance;
        }
        if ($request->booking_type == 'single') {
            $pd = explode(' ', $request->preferred_date);
            $preferred_date = Carbon::parse($pd[0] . " " . $request->preferred_time);
        } else {
            $pd = explode(' ', $request->preferred_date);
            $preferred_date = Carbon::parse($pd[0]);
        }
        $preferred_days_string = '';
        if ($request->preferred_days != null) {
            foreach ($request->preferred_days as $day) {
                $pDay = explode('-', $day);
                $preferred_days_string .= $pDay[0] . ' ' . date('h:i a', strtotime($pDay[1])) . ', ';
            }
        }
        if ($request->booking_type == 'single') {
            $wash_policy = "This booking includes a total of {$totalWashes} wash(es) and selected addons.";
            $refund_policy = "In case of refund you'll get a refund of amount Rs. {$totalPrice}.";
        } else {
            $wash_policy = "This booking includes a total of {$totalWashes} wash(es) and selected addons.";
            $prices = BasePrice::where('car_type_id', $customerCar->car->car_type_id)->get();
            $basePrice = $prices->where('wash_qty', '=', $totalWashes)->first();
            $amounts = [];
            for ($i = 1; $i <= $totalWashes; $i++) {
                $price = $prices->where('wash_qty', '=', $totalWashes - $i)->first();
                if ($i == $totalWashes) {
                    $refund = $basePrice->price;
                } else {
                    $refund =  $price ? ($basePrice->price - $price->price) : 0;
                }
                array_push($amounts, "Rs. {$refund} for {$i} wash cancellation");
            }
            $amount_string = implode(", ", $amounts);
            $refund_policy = "In case of refund you'll get a refund of amount {$amount_string}";
        }
        return response()->json([
            'customer_car' => fractal($customerCar, new CustomerCarTransformer())->toArray()['data'],
            'order_total_before_taxes' => sprintf("%.2f", $totalPrice),
            'order_total' => sprintf("%.2f", $totalPrice),
            'wallet_balance' => sprintf("%.2f", $wallet_balance),
            'final_price' => sprintf("%.2f", $totalPrice - $wallet_deduction_amount),
            'allow_wallet' => (bool) $allow_wallet,
            'wallet_deduction_amount' => sprintf("%.2f", $wallet_deduction_amount),
            'order_items' => $order_items,
            'preferred_date' => $preferred_date->toDayDateTimeString(),
            'preferred_days' => $preferred_days_string,
            'general_terms' => array_merge([$wash_policy], $this->repository->getGeneralTerms()),
            'cancellation_terms' => array_merge($this->repository->getCancellationTerms(), [$refund_policy])
        ], 200);
    }
}
