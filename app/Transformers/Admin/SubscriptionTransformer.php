<?php

namespace App\Transformers\Admin;

use App\Models\Subscription;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SubscriptionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Subscription $subscription
     * @return array
     */
    public function transform(Subscription $subscription)
    {
        return [
            'id' => $subscription->id,
            'code' => $subscription->code,
            'customer' => $subscription->customer->full_name,
            'customer_car' => $subscription->customerCar->car->full_name,
            'plan' => $subscription->plan->name,
            'total_billing_cycles' => $subscription->total_billing_cycles,
            'completed_billing_cycles' => $subscription->completed_billing_cycles,
            'last_renewed_date' => $subscription->last_renewed_date ? Carbon::parse($subscription->last_renewed_date)->toFormattedDateString() : '-',
            'next_renew_date' => $subscription->next_renew_date ? Carbon::parse($subscription->next_renew_date)->toFormattedDateString() : '-',
            'status' => ucwords($subscription->status),
            'invoices_count' => $subscription->invoices_count
        ];
    }
}
