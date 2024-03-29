<?php

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRequest extends FormRequest
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
            'name' => 'required',
            'shippingZones.*.name' => 'required',
            'shippingZones.*.price' => 'required|numeric|min:0|max:100000',
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'shippingZones.*.name.required' => __('Shipping zone name is required'),
        ]);
    }
}
