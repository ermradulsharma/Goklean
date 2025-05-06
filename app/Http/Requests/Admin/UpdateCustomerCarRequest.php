<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerCarRequest extends FormRequest
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
            'number_plate' => ['string', 'required', 'max:100', Rule::unique('customer_cars')->ignore($this->route('customer_car'))],
            'color' => ['string', 'required', 'max:100'],
        ];
    }
}
