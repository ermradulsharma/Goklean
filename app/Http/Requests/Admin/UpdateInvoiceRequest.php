<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'due_date' => ['required'],
            'status' => ['required'],
            'payment_mode' => ['required'],
            'reference_id' => ['required_if:payment_mode,online'],
            'payment_date' => ['required_if:status,paid'],
            'transaction_id' => ['required_if:status,paid'],
            'booking_completed' => ['required'],
            'preferences' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'You must select atleast one product.',
        ];
    }
}
