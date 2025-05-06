<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

class UpdateAdminRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'mobile' => ['required', 'string', 'max:10', Rule::unique('users')->ignore($this->route('admin'))],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->route('admin'))],
            'password' => ['nullable', 'string', new Password()],
            'city_id' => ['required']
        ];
    }
}
