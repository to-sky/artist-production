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
            'user.first_name' => 'required_without:address_id|string',
            'user.last_name' => 'required_without:address_id|string',
            'user.email' => 'required_without:address_id|email|unique:users,email',
            'user.email_confirm' => 'required_without:address_id|same:user.email',
            'user.phone' => 'required_without:address_id|regex:/\+?[0-9\-]+/i',
            'billing_address.street' => 'required_without:address_id|string',
            'billing_address.house' => 'required_without:address_id|string',
            'billing_address.apartment' => 'required_without:address_id|int',
            'billing_address.post_code' => 'required_without:address_id|string',
            'billing_address.city' => 'required_without:address_id|string',
            'billing_address.country_id' => 'required_without:address_id|exists:countries,id',
            'address_id' => 'exists:addresses,id',
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
            'required_without' => __('Mandatory field'),
            'confirmation.required' => __('Please accept out terms of use'),
            'user.phone.regex' => __('Wrong phone format'),
            'exists' => __('Not existing value selected'),
            'email' => __('Wrong email format'),
            'user.email.unique' => __('This email already registered'),
            'user.email_confirm.same' => __('Emails don\'t match'),
            'int' => __('Value must be an integer'),
        ];
    }
}
