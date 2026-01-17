<?php

namespace App\Traits;


use App\Models\SubCategory;
use Illuminate\Support\Facades\Cookie;

trait SubscriptionTrait
{
    /**
     * Check user had active subscription to the category
     *
     * @return bool
     */
    public function hasActiveSubscription()
    {
        $subscription = $this->subscriptions()
            ->where('category_id', '=', Cookie::get('category_id'))
            ->where('ends_at', '>', now()->toDateTimeString())
            ->where('status', '=', 'active')
            ->count();
        return $subscription > 0;
    }

    /**
     * Check user had pending bank payment for a plan
     *
     * @param $planId
     * @return bool
     */
    public function hasPendingBankPayment($planId)
    {
        $pendingBankPayments = $this->payments()
            ->where('plan_id', '=', $planId)
            ->where('payment_processor', '=', 'bank')
            ->where('status', '=', 'pending')
            ->count();
        return $pendingBankPayments > 0;
    }
}
