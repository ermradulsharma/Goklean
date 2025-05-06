<?php

namespace App\Transformers\Admin;

use App\Models\Referral;
use League\Fractal\TransformerAbstract;

class ReferralTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Referral $referral
     * @return array
     */
    public function transform(Referral $referral)
    {
        return [
            'id' => $referral->id,
            'code' => $referral->code,
            'customer' => $referral->customer->full_name,
            'referred_by' => $referral->referred_by->full_name,
            'amount' => $referral->amount,
            'status' => ucfirst($referral->status),
        ];
    }
}
