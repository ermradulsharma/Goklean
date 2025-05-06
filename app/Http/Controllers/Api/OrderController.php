<?php

namespace App\Http\Controllers\Api;


use App\Transformers\Api\OrderTransformer;

class OrderController
{
    /**
     * Get orders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders()
    {
        $customer = request()->user();
        $data = $customer->invoices()->whereIn('status', ['paid'])->with(['customerCar', 'subscription' => function ($query) {
            $query->with('plan:id,name');
        }])->orderBy('payment_date', 'DESC')->get();
        $orders = fractal($data, new OrderTransformer())->toArray();
        return response()->json($orders['data'], 200);
    }

    /**
     * Get unpaid invoices of a subscription
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoices()
    {
        $customer = request()->user();
        $data = $customer->invoices()->where('transaction_id', '=', null)->whereNotNull('subscription_id')->whereIn('status', ['created'])->with(['customerCar', 'subscription' => function ($query) {
            $query->with('plan:id,name');
        }])->get();
        $orders = fractal($data, new OrderTransformer())->toArray();
        return response()->json($orders['data'], 200);
    }
}
