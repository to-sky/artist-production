<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCheckoutRequest extends FormRequest
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
            'user.first_name' => 'required|string',
            'user.last_name' => 'required|string',
            'user.email' => 'required|email|unique:users,email',
            'user.email_confirm' => 'required|same:user.email',
            'user.phone' => 'required|regex:/\+?[0-9\-]+/i',
            'billing_address.street' => 'required|string',
            'billing_address.house' => 'required|string',
            'billing_address.apartment' => 'required|int',
            'billing_address.post_code' => 'required|string',
            'billing_address.city' => 'required|string',
            'billing_address.country_id' => 'required|exists:countries,id',
            'other_address_check' => 'boolean',
            'other_address.first_name' => 'required_with:other_address_check',
            'other_address.last_name' => 'required_with:other_address_check',
            'other_address.street' => 'required_with:other_address_check',
            'other_address.house' => 'required_with:other_address_check',
            'other_address.apartment' => 'required_with:other_address_check',
            'other_address.post_code' => 'required_with:other_address_check',
            'other_address.city' => 'required_with:other_address_check',
            'other_address.country_id' => 'required_with:other_address_check|exists:countries,id',
            'shipping_type' => 'nullable|exists:shippings,id',
            'courier' => 'boolean',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'confirmation' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => __('Mandatory field'),
            'required_with' => __('Mandatory field'),
            'confirmation.required' => __('Please accept out terms of use'),
            'user.phone.regex' => __('Wrong phone format'),
            'exists' => __('Unexisting value selected'),
            'email' => __('Wrong email format'),
            'user.email.unique' => __('This email already registered'),
            'user.email_confirm.same' => __('Email and Confirmation are different'),
            'imt' => __('Field must be an integer'),
        ];
    }
}
