<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required'],
            'car_id' => ['required'],
            'number_plate' => ['string', 'required', 'max:100', 'unique:customer_cars'],
            'color' => ['string', 'required', 'max:100'],
        ];
    }
}
