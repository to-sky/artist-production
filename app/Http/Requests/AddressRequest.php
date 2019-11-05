<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'address.first_name' => 'required|string',
            'address.last_name' => 'required|string',
            'address.country_id' => 'required|exists:countries,id',
            'address.city' => 'required|string',
            'address.post_code' => 'required|int',
            'address.street' => 'required|string',
            'address.house' => 'required|string',
            'address.apartment' => 'required|int'
        ];
    }

    public function messages()
    {
        return [
            'required' => __('Mandatory field'),
            'address.post_code.int' => __('Wrong post code format'),
            'address.apartment.int' => __('Wrong apartment format'),
        ];
    }
}
