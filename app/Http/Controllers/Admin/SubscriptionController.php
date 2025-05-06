<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerCar;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Subscription;
use App\Repositories\PricingRepository;
use App\Repositories\RazorpayRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
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
     * Start the subscription and create first invoice
     *
     * @param $id
     * @param PricingRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start($id, PricingRepository $repository)
    {
        $subscription = Subscription::withCount('invoices')->find($id);

        if($subscription->invoices_count > 0) {
            return redirect()->back()->with('errorMessage', 'Subscription has already started. Please try renew.');
        }
        $customer = Customer::findOrFail($subscription->customer_id);
        $car = CustomerCar::with('car')->findOrFail($subscription->customer_car_id);

        // fetch plan with discounts
        $group = $customer->user_group_id;
        $plan = Plan::with(['discounts' => function($query) use($group) {
            $query->where('user_group_id', '=', $group)->where('is_active', 1);
        }])->findOrFail($subscription->plan_id);
        $addons = $subscription->items()->where('product_type', '=', 'addon')->get();

        $products = $repository->fetchSubscriptionProductsWithPricing($plan, $addons, $car->car->car_type_id, $subscription->customer->user_group_id);

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

        $invoice = new Invoice();
        $invoice->invoice_id = 'GK'.time();
        $invoice->customer_id = $customer->id;
        $invoice->customer_car_id = $car->id;
        $invoice->order_type = 'bulk';
        $invoice->subscription_id = $subscription->id;
        $invoice->due_date = Carbon::parse($subscription->starts_at)->subDay()->endOfDay()->toDateTimeString();
        $invoice->billing_cycle_starts = Carbon::parse($subscription->starts_at)->startOfDay()->toDateTimeString();
        $invoice->billing_cycle_ends = Carbon::parse($subscription->starts_at)->addDays(27)->endOfDay()->toDateTimeString();
        $invoice->payment_mode = $subscription->payment_mode;
        $invoice->status = 'created';
        $invoice->billing_cycle = 1;
        $invoice->booking_completed = 0;
        $invoice->total_price = $totalPrice;

        $invoice->preferences->set($subscription->preferences);

        $invoice->data->set([
            'items' => $products
        ]);

        $invoice->save();

        // Save Invoice Items
        if($invoice) {
            $invoice->items()->sync($items);
        }

        $subscription->completed_billing_cycles = $subscription->invoices->count();
        $subscription->next_renew_date = Carbon::parse($subscription->starts_at)->addDays(28)->startOfDay()->toDateTimeString();
        $subscription->update();

        return redirect()->back()->with('successMessage', 'Subscription successfully started');
    }

    /**
     * Renew the subscription and generate invoice for the next billing cycle
     *
     * @param $id
     * @param PricingRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function renew($id, PricingRepository $repository)
    {
        $subscription = Subscription::withCount('invoices')->find($id);
        $prevInvoice = $subscription->invoices()->orderBy('billing_cycle', 'desc')->first();

        // check if subscription has first invoice
        if($subscription->invoices_count == 0) {
            return redirect()->back()->with('errorMessage', 'Unable to renew! Subscription not yet started.');
        }

        // check for previous invoice status
        if($prevInvoice->status !== 'paid' && $prevInvoice->status !== 'cancelled') {
            return redirect()->back()->with('errorMessage', "Unable to renew! Previous invoice {$prevInvoice->invoice_id} is not paid/cancelled.");
        }

        // check if previous invoice paid and no booking
        if($prevInvoice->status == 'paid' && !$prevInvoice->booking_completed) {
            return redirect()->back()->with('errorMessage', "Unable to renew! Previous invoice {$prevInvoice->invoice_id} is paid but no booking is created.");
        }

        $customer = Customer::findOrFail($subscription->customer_id);
        $car = CustomerCar::with('car')->findOrFail($subscription->customer_car_id);

        // fetch plan with discounts
        $group = $customer->user_group_id;
        $plan = Plan::with(['discounts' => function($query) use($group) {
            $query->where('user_group_id', '=', $group)->where('is_active', 1);
        }])->findOrFail($subscription->plan_id);
        $addons = $subscription->items()->where('product_type', '=', 'addon')->get();

        $products = $repository->fetchSubscriptionProductsWithPricing($plan, $addons, $car->car->car_type_id, $subscription->customer->user_group_id);

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
        $invoiceCode = 'GK'.rand(1111111111,time());
        $order = [];
        if($subscription->payment_mode == 'online') {
            try {
                $order = $this->razorpayRepository->createOrder($invoiceCode, $totalPrice * 100);
            } catch (\Exception $exception) {
                return redirect()->back()->with('errorMessage', 'Unable to create razorpay order.');
            }
        }

        $invoice = new Invoice();
        $invoice->invoice_id = $invoiceCode;
        $invoice->customer_id = $customer->id;
        $invoice->customer_car_id = $car->id;
        $invoice->order_type = 'bulk';
        $invoice->subscription_id = $subscription->id;
        $invoice->due_date = $prevInvoice->billing_cycle_ends;
        $invoice->payment_mode = $subscription->payment_mode;
        $invoice->reference_id = $subscription->payment_mode == 'online' ? $order['id'] : null;
        $invoice->status = 'created';
        $invoice->billing_cycle = $subscription->invoices_count+1;
        $invoice->billing_cycle_starts = Carbon::parse($prevInvoice->billing_cycle_ends)->addDay()->startOfDay()->toDateTimeString();
        $invoice->billing_cycle_ends = Carbon::parse($prevInvoice->billing_cycle_ends)->addDays(28)->endOfDay()->toDateTimeString();
        $invoice->booking_completed = 0;
        $invoice->total_price = $totalPrice;

        $invoice->preferences->set($subscription->preferences);

        $invoice->data->set([
            'items' => $products
        ]);

        $invoice->save();

        // Save Invoice Items
        if($invoice) {
            $invoice->items()->sync($items);
        }

        $subscription->completed_billing_cycles = $subscription->invoices->count();
        $subscription->next_renew_date = Carbon::parse($prevInvoice->billing_cycle_ends)->addDays(29)->startOfDay()->toDateTimeString();
        $subscription->update();

        return redirect()->back()->with('successMessage', 'Subscription successfully renewed');
    }
}
