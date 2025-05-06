<?php

namespace App\Transformers;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerSearchTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Customer $customer
     * @return array
     */
    public function transform(Customer $customer)
    {
        return [
            'id' => $customer->id,
            'name' => $customer->full_name
        ];
    }
}
