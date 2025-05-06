<?php

namespace App\Transformers\Admin;

use App\Models\Invoice;
use League\Fractal\TransformerAbstract;

class InvoiceSearchTransformer extends TransformerAbstract
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
            'name' => $invoice->invoice_id.' ('.ucfirst($invoice->order_type).')'
        ];
    }
}
