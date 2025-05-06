<?php

namespace App\Transformers\Api;

use App\Models\Invoice;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Invoice $invoice)
    {
        return [
            'id' => (int) $invoice->id,
            'code' => (string) $invoice->invoice_id,
            'date' => (string) $invoice->status == 'created'
                ? Carbon::parse($invoice->due_date)->toFormattedDateString()
                : Carbon::parse($invoice->payment_date)->toFormattedDateString(),
            'is_subscription_order' => (bool) $invoice->subscription_id != null,
            'subscription' => (string) $invoice->subscription_id != null ? $invoice->subscription->plan->name : '',
            'price' => (string) $invoice->total_price,
            'car_name' => (string) strtoupper($invoice->customerCar->car->full_name),
            'number_plate' => (string) $invoice->customerCar->number_plate,
            'image_path' => (string) asset($invoice->customerCar->car->image_path),
            'items'  => (string) $this->getItemsText($invoice->data->get('items', [])),
            'reference_id' => (string) $invoice->reference_id,
            'transaction_id' => (string) $invoice->transaction_id,
            'status' => $invoice->status == 'created' ? 'Unpaid' : ucfirst($invoice->status),
        ];
    }

    public function getItemsText($items)
    {
        $string = '';
        foreach ($items as $key => $item) {
            $string .= $item['name'];
            count($items) != $key+1 ? $string .= ", " : $string .= '';
        }
        return $string;
    }
}
