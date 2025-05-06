<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'item_type' => ['required'],
            'item_id' => ['required'],
            'discount_type' => ['required'],
            'discount_value' => ['required'],
            'valid_from' => ['required'],
            'valid_until' => ['required'],
            'maximum_discount_value' => ['required'],
            'minimum_cart_amount' => ['required'],
            'is_private' => ['required'],
            'user_group_id' => ['required_if:is_private,true'],
            'is_active' => ['required']
        ];
    }
}
