<?php

namespace App\Transformers\Admin;

use App\Models\Invoice;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
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
            'invoice_id' => $invoice->invoice_id,
            'order_type' => ucwords($invoice->order_type),
            'customer' => $invoice->customer->full_name,
            'customer_car' => $invoice->customerCar->car->full_name,
            'total_price' => '₹'.$invoice->total_price,
            'payment_mode' => ucwords($invoice->payment_mode),
            'due_date' => $invoice->due_date ? Carbon::parse($invoice->due_date)->toDayDateTimeString() : '-',
            'payment_date' => $invoice->payment_date ? Carbon::parse($invoice->payment_date)->toDayDateTimeString() : '-',
            'booking_completed' => $invoice->booking_completed,
            'subscription_id' => $invoice->subscription_id,
            'items' => $invoice->data->get('items', []),
            'status' => ucwords($invoice->status)
        ];
    }
}
