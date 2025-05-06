<?php

namespace App\Transformers\Api;

use League\Fractal\TransformerAbstract;

class CustomerAddressTransformer extends TransformerAbstract
{
    public function transform($address)
    {
        return [
            'address_type'       => (string) $address->address_type,
            'flat_no'       => (string) $address->flat_no,
            'flat_name'       => (string) $address->flat_name,
            'address'       => (string) $address->address,
            'area'       => (string) $address->area,
            'latitude'       => (string) $address->latitude,
            'longitude'       => (string) $address->longitude,
            'is_verified'       => (boolean) $address->is_verified,
        ];
    }
}
