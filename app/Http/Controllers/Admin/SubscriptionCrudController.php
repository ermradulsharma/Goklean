<?php

namespace App\Http\Controllers\Admin;

use App\Filters\SubscriptionFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubscriptionRequest;
use App\Http\Requests\Admin\UpdateSubscriptionRequest;
use App\Models\Customer;
use App\Models\CustomerCar;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Repositories\BookingRepository;
use App\Transformers\Admin\CustomerCarTransformer;
use App\Transformers\Admin\CustomerTransformer;
use App\Transformers\Admin\SubscriptionInvoiceTransformer;
use App\Transformers\Admin\SubscriptionTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionCrudController extends Controller
{
    /**
     * List all Subscriptions
     *
     * @param SubscriptionFilters $filters
     * @return \Inertia\Response
     */
    public function index(SubscriptionFilters $filters)
    {
        $subscriptions = fn() => fractal(Subscription::with(['customer', 'customerCar.car', 'plan'])->withCount('invoices')->filter($filters)->orderByDesc('next_renew_date')->paginate(request()->perPage ?? 10), new SubscriptionTransformer())->toArray();
        $plans = Plan::active()->get(['id', 'name']);
        return Inertia::render('Admin/Subscriptions', ['subscriptions' => $subscriptions, 'plans' => $plans]);
    }

    public function create(Request $request, BookingRepository $bookingRepository)
    {
        $car = CustomerCar::with('car')->findOrFail($request->customer_car_id);
        $customer = Customer::findOrFail($car->customer_id);
        $plan = Plan::findOrFail($request->plan_id);
        $qty = $plan->wash_qty_per == 'monthly' ? $plan->wash_qty : $plan->wash_qty * 4;
        $products = Product::active()->get()->map(function ($product, $key) use ($qty) {
            $isGKProduct = $product->code === 'GK-EXT-1W';
            return [
                "id" => $product->id,
                "name" => $product->name,
                "code" => $product->code,
                "product_type" => ucfirst($product->product_type),
                "qty" => $isGKProduct ? $qty : 1,
                "selected" => $isGKProduct,
            ];
        });

        return Inertia::render('Admin/Forms/SubscriptionForm', [
            'customer' => fractal($customer, new CustomerTransformer())->toArray()['data'],
            'car' => fractal($car, new CustomerCarTransformer())->toArray()['data'],
            'plan' => $plan,
            'products' => $products->values(),
            'preferences' => $bookingRepository->bulkWashPreferences(),
            'paymentModes' => [
                ['code' => 'online', 'name' => 'Online'],
                ['code' => 'offline', 'name' => 'Offline'],
            ],
            'statuses' => [
                ['code' => 'created', 'name' => 'Created'],
                ['code' => 'pending', 'name' => 'Pending'],
                ['code' => 'expired', 'name' => 'Expired'],
                ['code' => 'active', 'name' => 'Active'],
                ['code' => 'cancelled', 'name' => 'Cancelled']
            ]
        ]);
    }

    /**
     * Store a Subscription
     *
     * @param StoreSubscriptionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSubscriptionRequest $request)
    {
        $car = CustomerCar::with('car')->findOrFail($request->customer_car_id);
        $customer = Customer::findOrFail($car->customer_id);
        $items = [];

        foreach ($request->products as $key => $product) {
            if ($product['selected']) {
                $items[$product['id']] = [
                    'qty' => $product['qty'],
                ];
            }
        }

        $subscription = new Subscription();
        $subscription->code = 'SUB' . time();
        $subscription->customer_id = $customer->id;
        $subscription->plan_id = $request->plan_id;
        $subscription->customer_car_id = $car->id;
        $subscription->payment_mode = $request->payment_mode;
        $subscription->starts_at = $request->starts_at;
        $subscription->total_billing_cycles = $request->total_billing_cycles;
        $subscription->status = $request->status;
        $subscription->preferences->set($request->preferences);
        $subscription->save();

        // Save Subscriptions Items
        if ($subscription) {
            $subscription->items()->sync($items);
        }

        return redirect()->route('subscriptions.index')->with('successMessage', 'Subscription was successfully added!');
    }

    /**
     * Show a Subscription
     *
     * @param $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $subscription = Subscription::withCount('invoices')->with(['invoices' => function ($query) {
            $query->orderBy('billing_cycle', 'desc');
        }, 'items'])->find($id);
        $items = [];
        foreach ($subscription->items as $item) {
            array_push($items, [
                'id' => $item['id'],
                'code' => $item['code'],
                'name' => $item['name'],
                'qty' => $item['pivot']['qty'],
            ]);
        }
        $days = [];
        foreach ($subscription['preferences']['days'] as $day) {
            if ($day['selected']) {
                array_push($days, [
                    'name' => $day['name'],
                    'time' => date("h:i A", strtotime($day['time']))
                ]);
            }
        }
        return Inertia::render('Admin/SubscriptionDashboard', [
            'subscription' => fractal($subscription, new SubscriptionTransformer())->toArray()['data'],
            'invoices' => fractal($subscription->invoices, new SubscriptionInvoiceTransformer())->toArray()['data'],
            'items' => $items,
            'days' => $days
        ]);
    }

    /**
     * Edit a Subscription
     *
     * @param $id
     * @param BookingRepository $bookingRepository
     * @return \Inertia\Response
     */
    public function edit($id, BookingRepository $bookingRepository)
    {
        $subscription = Subscription::with(['plan', 'customer', 'customerCar' => function ($query) {
            $query->with('car');
        }, 'items'])->find($id);

        $qty = $subscription->plan->wash_qty_per == 'monthly' ? $subscription->plan->wash_qty : $subscription->plan->wash_qty * 4;

        $products = Product::active()->get()->map(function ($product) use ($qty, $subscription) {
            $item = $subscription->items->where('id', '=', $product->id)->first();
            return [
                "id" => $product->id,
                "name" => $product->name,
                "code" => $product->code,
                "product_type" => ucfirst($product->product_type),
                "qty" => $item ? $item->pivot->qty : 1,
                "selected" => $item !== null
            ];
        });

        return Inertia::render('Admin/Forms/SubscriptionForm', [
            'subscription' => $subscription,
            'editFlag' => true,
            'customer' => fractal($subscription->customer, new CustomerTransformer())->toArray()['data'],
            'car' => fractal($subscription->customerCar, new CustomerCarTransformer())->toArray()['data'],
            'plan' => $subscription->plan,
            'products' => $products->all(),
            'preferences' => $bookingRepository->bulkWashPreferences(),
            'paymentModes' => [
                ['code' => 'online', 'name' => 'Online'],
                ['code' => 'offline', 'name' => 'Offline'],
            ],
            'statuses' => [
                ['code' => 'created', 'name' => 'Created'],
                ['code' => 'pending', 'name' => 'Pending'],
                ['code' => 'expired', 'name' => 'Expired'],
                ['code' => 'active', 'name' => 'Active'],
                ['code' => 'cancelled', 'name' => 'Cancelled']
            ]
        ]);
    }

    /**
     * Update a Subscription
     *
     * @param UpdateSubscriptionRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubscriptionRequest $request, $id)
    {
        $subscription = Subscription::find($id);
        $items = [];
        $subscription->update($request->validated());

        foreach ($request->products as $key => $product) {
            if ($product['selected']) {
                $items[$product['id']] = [
                    'qty' => $product['qty'],
                ];
            }
        }

        // Save Subscriptions Items
        if ($subscription) {
            $subscription->items()->sync($items);
        }

        return redirect()->route('subscriptions.index')->with('successMessage', 'Subscription was successfully updated!');
    }

    /**
     * Delete a Subscription
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $plan = Subscription::find($id);

            if (!$plan->canSecureDelete('invoices')) {
                return redirect()->back()->with('errorMessage', 'Unable to Delete Subscription! Remove from all associations and try again!');
            }

            $plan->secureDelete('invoices');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('errorMessage', 'Unable to Delete Subscription . Remove all associations and Try again!');
        }

        return redirect()->back()->with('successMessage', 'Subscription was successfully deleted!');
    }
}
