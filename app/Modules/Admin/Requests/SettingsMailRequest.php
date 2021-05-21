<?php

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsMailRequest extends FormRequest {

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
            'settings.order_payment_copy_email' => 'email',
            'settings.administrator_email' => 'email'
		];
	}

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'settings.order_payment_copy_email' => __('Send copy of notification about successful order payment to email'),
            'settings.administrator_email' => __('Administrator email')
        ];
    }
}
