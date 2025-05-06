<?php

namespace App\Transformers\Api;

use App\Models\Customer;
use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    public function transform(Customer $customer)
    {
        return [
            'id' => (int)$customer->id,
            'name' => (string)$customer->first_name,
            'email' => (string)$customer->email,
            'mobile' => (string)$customer->mobile,
            'alt_mobile' => (string)$customer->alt_mobile,
            'city' => (object)['id' => $customer->city->id, 'name' => $customer->city->name],
            'has_address' => $customer->preferences->get('address') != null,
            'email_verified' => (boolean) $customer->email_verified_at != null,
            'mobile_verified' => (boolean)$customer->mobile_verified_at != null,
            'address_verified' => (boolean) $customer->preferences->get('address.approved', false),
            'referral_code' => (string)$customer->getUniqueAttribute()
        ];
    }
}
