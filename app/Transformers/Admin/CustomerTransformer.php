<?php

namespace App\Transformers\Admin;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
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
            'code' => $customer->unique_code,
            'name' => $customer->full_name,
            'city' => $customer->city->name,
            'email' => $customer->email,
            'mobile' => $customer->mobile,
            'status' => $customer->is_active,
            'balance' => '₹' . $customer->balanceFloat,
            'wallet_balance' => $customer->wallet ? $customer->wallet->balance : 0,
            'transactions' => $customer->transactions->toArray(),
        ];
    }
}
