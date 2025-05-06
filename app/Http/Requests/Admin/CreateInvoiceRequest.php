<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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
            'order_type' => ['required'],
            'due_date' => ['required'],
            'status' => ['required'],
            'payment_mode' => ['required'],
            'reference_id' => ['required_if:payment_mode,online'],
            'transaction_id' => ['required_if:status,paid'],
            'payment_date' => ['required_if:status,paid'],
            'booking_completed' => ['required'],
            'products' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'You must select at least one product.',
        ];
    }
}
