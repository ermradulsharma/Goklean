<?php

namespace App\Http\Controllers\Api;


use App\Transformers\Api\SubscriptionTransformer;

class SubscriptionController
{
    public function getSubscriptions()
    {
        $customer = request()->user();
        $data = $customer->subscriptions()->with(['customerCar', 'plan'])->get();
        $subscriptions = fractal($data, new SubscriptionTransformer())->toArray();
        return response()->json($subscriptions['data'], 200);
    }

    public function subscriptionHistory()
    {
        $customer = request()->user()->customer;
        $data = $customer->subscriptions()->whereIn('razorpay_status', ['active', 'completed'])->with(['customerCar', 'plan'])->get();
        $subscriptions = fractal($data, new SubscriptionTransformer())->toArray();
        return response()->json($subscriptions['data'], 200);
    }
}
