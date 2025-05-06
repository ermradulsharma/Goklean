<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
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
            'payment_mode' => ['required'],
            'starts_at' => ['required'],
            'status' => ['required'],
            'total_billing_cycles' => ['required'],
            'last_renewed_date' => ['nullable'],
            'next_renew_date' => ['required'],
            'preferences' => ['required'],
        ];
    }
}
