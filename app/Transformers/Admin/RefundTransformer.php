<?php

namespace App\Transformers\Admin;

use App\Models\Refund;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RefundTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Refund $refund
     * @return array
     */
    public function transform(Refund $refund)
    {
        return [
            'id' => $refund->id,
            'code' => $refund->code,
            'customer' => $refund->customer->full_name,
            'booking' => $refund->booking->code,
            'type' => ucwords($refund->refund_type).' Amount',
            'amount' => '₹'.$refund->amount,
            'date' => Carbon::parse($refund->refund_date)->toDayDateTimeString(),
            'status' => ucwords($refund->status),
            'approved' => $refund->status == 'completed'
        ];
    }
}
