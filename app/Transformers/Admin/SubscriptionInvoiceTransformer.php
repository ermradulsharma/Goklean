<?php

namespace App\Transformers\Admin;

use App\Models\Invoice;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class SubscriptionInvoiceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Invoice $invoice
     * @return array
     */
    public function transform(Invoice $invoice)
    {
        return [
            'id' => $invoice->id,
            'code' => $invoice->invoice_id,
            'billing_cycle' => $invoice->billing_cycle,
            'billing_cycle_starts' => Carbon::parse($invoice->billing_cycle_starts)->toFormattedDateString(),
            'billing_cycle_ends' => Carbon::parse($invoice->billing_cycle_ends)->toFormattedDateString(),
            'booking_status' => $invoice->booking_completed ? 'Completed' : 'Pending',
            'status' => ucwords($invoice->status),
        ];
    }
}
