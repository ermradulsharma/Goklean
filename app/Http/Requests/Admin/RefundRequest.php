<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
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
            'refund_type' => ['required'],
            'refund_amount' => ['required_if:refund_type,full'],
            'custom_amount' => ['required_if:refund_type,custom'],
            'notes' => ['nullable']
        ];
    }
}
