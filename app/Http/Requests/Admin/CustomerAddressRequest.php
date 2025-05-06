<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
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
             'address' =>  ['required', 'string'],
            'area' =>  ['required', 'string'],
            'latitude' =>  ['required'],
            'longitude' =>  ['required'],
            'address_type' => ['required'],
            'house_no' =>  ['required'],
            'house_name' =>  ['required'],
            'city_id' =>  ['required'],
            'approved' => ['required'],
			'day_time_list' => ['required'],
        ];
    }
}
