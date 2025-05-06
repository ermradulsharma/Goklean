<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\OrderTransformer;
use App\Models\BasePrice;
use App\Models\CustomerCar;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Repositories\BookingRepository;
use App\Repositories\PricingRepository;
use App\Repositories\RazorpayRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentController extends Controller
{
    /**
     * @var RazorpayRepository
     */
    private $razorpayRepository;

    public function __construct(RazorpayRepository $razorpayRepository)
    {
        $this->razorpayRepository = $razorpayRepository;
    }

    /**
     * Create order for single booking
     *
     * @param Request $request
     * @param PricingRepository $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request, PricingRepository $repository)
    {
        $validator = Validator::make($request->all(), [
            'customer_car_id' => 'required',
            'product_id' => 'required',
            'booking_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $customer = request()->user();
        $customerCar = CustomerCar::with(['car', 'customer'])->find($request->customer_car_id);
        $carType = $customerCar->car->car_type_id;
        $totalPrice = 0;
        $tax_amount = 0;
        $products = [];
        $items = []; // For saving invoice items into database
        $invoiceProducts = []; //  For saving invoice data
        array_push($products, $request->product_id);
        if ($request->addons != null) {
            foreach ($request->addons as $addon) {
                array_push($products, $addon['id']);
            }
        }
        $estimateProducts = $repository->fetchEstimateProducts($products, $carType, $customerCar->customer->user_group_id);
        foreach ($estimateProducts['data'] as $key => $product) {
            $subTotal = $product['has_discount'] ? $product['qty'] * $product['discounted_price'] : $product['qty'] * $product['price'];
            $totalPrice += $subTotal;
            array_push($invoiceProducts, [
                'id' => $product['id'],
                'code' => $product['code'],
                'name' => $product['name'],
                'qty' => $product['qty'],
                'price' => $product['price'],
                'product_type' => ucfirst($product['type']),
                'has_discount' => $product['has_discount'],
                'discounted_price' => $product['discounted_price']
            ]);
            $items[$product['id']] = [
                'qty' => $product['qty'],
                'price' => $product['has_discount'] ? $product['discounted_price'] : $product['price'],
                'sub_total' => $subTotal
            ];
        }
        $pd = explode(' ', $request->preferred_date);
        $preferred_date = Carbon::parse($pd[0] . " " . $request->preferred_time);
        $invoiceCode = 'GK' . time();
        try {
            $order = $this->razorpayRepository->createOrder($invoiceCode, $totalPrice * 100);
            $invoice = new Invoice();
            $invoice->invoice_id = $invoiceCode;
            $invoice->customer_id = $customer->id;
            $invoice->customer_car_id = $customerCar->id;
            $invoice->order_type = 'single';
            $invoice->due_date = Carbon::now()->toDateTimeString();
            $invoice->payment_mode = 'online';
            $invoice->reference_id = $order['id'];
            $invoice->status = 'created';
            $invoice->total_price = $totalPrice;
            $invoice->preferences->set('date_time', $preferred_date->toDateTimeString());
            $invoice->data->set([
                'items' => $invoiceProducts
            ]);
            $invoice->save();
            if ($invoice) {
                $invoice->items()->sync($items);
            }
            return response()->json([
                'order_id' => $invoice->invoice_id,
                'rzp_order_id' => $order['id'],
                'email' => $customer->email,
                'mobile' => '91' . $customer->mobile
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['errors' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * Create subscription and first order for bulk booking
     *
     * @param Request $request
     * @param PricingRepository $repository
     * @param BookingRepository $bookingRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAuthenticateSubscription(Request $request, PricingRepository $repository, BookingRepository $bookingRepository)
    {
        $validator = Validator::make($request->all(), [
            'customer_car_id' => 'required',
            'plan_id' => 'required',
            'booking_type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $customer = request()->user();
        $customerCar = CustomerCar::with(['car', 'customer'])->find($request->customer_car_id);
        $subscriptionItems = [];
        $plan = Plan::find($request->plan_id);
        $wash_qty = $plan->wash_qty_per == "monthly" ? $plan->wash_qty : (int) $plan->wash_qty * 4;
        $product = Product::where('code', '=', 'GK-EXT-1W')->first();
        $subscriptionItems[$product['id']] = [
            'qty' => $wash_qty,
        ];
        if ($request->addons != null) {
            foreach ($request->addons as $addon) {
                $subscriptionItems[$addon['id']] = [
                    'qty' => $addon['qty'],
                ];
            }
        }
        $subscription = new Subscription();
        $subscription->code = 'SUB' . rand(1111111111, time());
        $subscription->customer_id = $customer->id;
        $subscription->plan_id = $plan->id;
        $subscription->customer_car_id = $customerCar->id;
        $subscription->payment_mode = 'online';
        $subscription->starts_at = Carbon::parse($request->preferred_date)->toDateTimeString();
        $subscription->total_billing_cycles = 12;
        $subscription->status = 'created';
        if ($request->preferred_days != null) {
            $days = $bookingRepository->bulkWashPreferences()['days'];
            foreach ($request->preferred_days as $day) {
                $pDay = explode('-', $day);
                $days[getDayFromString($pDay[0])]['time'] = date('H:i:s', strtotime($pDay[1]));
                $days[getDayFromString($pDay[0])]['selected'] = true;
            }
        } else {
            $days = $bookingRepository->bulkWashPreferences()['days'];
        }
        $subscription->preferences->set('days', $days);
        $subscription->save();
        if ($subscription) {
            $subscription->items()->sync($subscriptionItems);
        }
        $group = $customer->user_group_id;
        $plan = Plan::with(['discounts' => function ($query) use ($group) {
            $query->where('user_group_id', '=', $group)->where('is_active', 1);
        }])->findOrFail($subscription->plan_id);
        $addons = $subscription->items()->where('product_type', '=', 'addon')->get();
        $products = $repository->fetchSubscriptionProductsWithPricing($plan, $addons, $customerCar->car->car_type_id, $group);
        $totalPrice = 0;
        $items = [];
        foreach ($products as $key => $product) {
            $price = $product['has_discount'] ? $product['discounted_price'] : $product['price'];
            $subTotal = $product['qty'] * $price;
            $totalPrice += $subTotal;
            $items[$product['id']] = [
                'qty' => $product['qty'],
                'price' => $price,
                'sub_total' => $subTotal
            ];
        }
        $invoiceCode = 'GK' . rand(1111111111, time());
        try {
            $order = $this->razorpayRepository->createOrder($invoiceCode, $totalPrice * 100);
            $invoice = new Invoice();
            $invoice->invoice_id = $invoiceCode;
            $invoice->customer_id = $customer->id;
            $invoice->customer_car_id = $customerCar->id;
            $invoice->order_type = 'bulk';
            $invoice->subscription_id = $subscription->id;
            $invoice->due_date = Carbon::parse($subscription->starts_at)->subDay()->endOfDay()->toDateTimeString();
            $invoice->billing_cycle_starts = Carbon::parse($subscription->starts_at)->startOfDay()->toDateTimeString();
            $invoice->billing_cycle_ends = Carbon::parse($subscription->starts_at)->addDays(27)->endOfDay()->toDateTimeString();
            $invoice->payment_mode = 'online';
            $invoice->reference_id = $order['id'];
            $invoice->status = 'created';
            $invoice->billing_cycle = 1;
            $invoice->booking_completed = 0;
            $invoice->total_price = $totalPrice;
            $invoice->preferences->set($subscription->preferences);
            $invoice->data->set([
                'items' => $products
            ]);
            $invoice->save();
            if ($invoice) {
                $invoice->items()->sync($items);
            }
            $subscription->completed_billing_cycles = $subscription->invoices->count();
            $subscription->next_renew_date = Carbon::parse($subscription->starts_at)->addDays(28)->startOfDay()->toDateTimeString();
            $subscription->update();
            return response()->json([
                'order_id' => $invoice->invoice_id,
                'rzp_order_id' => $order['id'],
                'email' => $customer->email,
                'mobile' => '91' . $customer->mobile
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['errors' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * Verify order for single booking
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyUpdateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_signature' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $verified = $this->razorpayRepository->verifyPayment([
            'razorpay_signature' => $request->get('razorpay_signature'),
            'razorpay_payment_id' => $request->get('razorpay_payment_id'),
            'razorpay_order_id' => $request->get('razorpay_order_id')
        ]);
        if ($verified) {
            $invoice = Invoice::where('reference_id', '=', $request->get('razorpay_order_id'))->first();
            $invoice->payment_date = Carbon::now()->toDateTimeString();
            $invoice->transaction_id = $request->get('razorpay_payment_id');
            $invoice->status = 'paid';
            $invoice->data->set([
                'razorpay' => $validator->validated()
            ]);
            $invoice->save();
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 200);
        }
    }

    /**
     * Verify subscription order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyUpdateSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_signature' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $verified = $this->razorpayRepository->verifyPayment([
            'razorpay_signature' => $request->get('razorpay_signature'),
            'razorpay_payment_id' => $request->get('razorpay_payment_id'),
            'razorpay_order_id' => $request->get('razorpay_order_id')
        ]);

        if ($verified) {
            $invoice = Invoice::where('reference_id', '=', $request->get('razorpay_order_id'))->first();
            $invoice->payment_date = Carbon::now()->toDateTimeString();
            $invoice->transaction_id = $request->get('razorpay_payment_id');
            $invoice->status = 'paid';
            $invoice->data->set([
                'razorpay' => $validator->validated()
            ]);
            $invoice->save();
            $subscription = Subscription::find($invoice->subscription_id);
            $paymentDate = Carbon::now();
            $billingCycleStarts = Carbon::parse($invoice->billing_cycle_starts);
            $subscription->last_renewed_date = Carbon::parse($paymentDate)->startOfDay()->toDateTimeString();
            if ($paymentDate->greaterThan($billingCycleStarts)) {
                $subscription->next_renew_date = Carbon::parse($paymentDate)->addDays(29)->startOfDay()->toDateTimeString();
            } else {
                $subscription->next_renew_date = Carbon::parse($invoice->billing_cycle_ends)->addDay()->startOfDay()->toDateTimeString();
            }
            $subscription->status = 'active';
            $subscription->update();
            if ($paymentDate->greaterThan($billingCycleStarts)) {
                $invoice->billing_cycle_starts = Carbon::parse($paymentDate)->addDay()->startOfDay()->toDateTimeString();
                $invoice->billing_cycle_ends = Carbon::parse($paymentDate)->addDays(28)->endOfDay()->toDateTimeString();
                $invoice->update();
            }
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 200);
        }
    }
}
