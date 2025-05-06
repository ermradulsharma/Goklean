<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:60', Rule::unique('plans')->ignore($this->route('plan'))],
            'description' => ['required', 'string', 'max:255'],
            'wash_qty_per' => ['required'],
            'wash_qty' => ['required'],
            'is_popular' => ['required'],
            'sort_order' => ['required'],
            'is_active' => ['required'],
        ];
    }
}
