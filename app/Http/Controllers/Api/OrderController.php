<?php

namespace App\Http\Controllers\Api;


use App\Transformers\Api\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function getInvoices(Request $request)
    {
        try {
            $customer = $request->user();
            if (!$customer) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }
            $invoices = $customer->invoices()->whereNull('transaction_id')->whereNotNull('subscription_id')->whereIn('status', ['created'])->with([
                'customerCar',
                'subscription' => function ($query) {
                    $query->with('plan:id,name');
                }
            ])->get();
            if ($invoices->isEmpty()) {
                return response()->json(['message' => 'No invoices found.'], 404);
            }
            $orders = fractal($invoices, new OrderTransformer())->toArray();
            return response()->json($orders['data'] ?? [], 200);
        } catch (\Exception $e) {
            Log::error('Invoice retrieval failed: ' . $e->getMessage(), [
                'user_id' => optional($request->user())->id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch invoices. Please try again later.'], 500);
        }
    }
}
