<?php

namespace App\Transformers\Api;

use App\Models\Subscription;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SubscriptionTransformer extends TransformerAbstract
{
    public function transform(Subscription $subscription)
    {
        return [
            'id'  => (int) $subscription->id,
            'code' => (string) $subscription->code,
            'plan' => (string) $subscription->plan->name,
            'car_name' => (string) $subscription->customerCar->car->full_name,
            'number_plate' => (string) $subscription->customerCar->number_plate,
            'image_path' => (string) asset($subscription->customerCar->car->image_path),
            'status' => ucfirst($subscription->status),
            'total_billing_cycles' => (string) "{$subscription->completed_billing_cycles}/{$subscription->total_billing_cycles}",
            'start_date' => Carbon::parse($subscription->starts_at)->toFormattedDateString(),
            'last_renewed_date' => $subscription->last_renewed_date != null ? Carbon::parse($subscription->last_renewed_date)->toFormattedDateString() : '',
            'next_renew_date' =>  $subscription->next_renew_date != null ? Carbon::parse($subscription->next_renew_date)->toFormattedDateString() : '',
        ];
    }
}
