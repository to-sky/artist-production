<?php

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReportRequest extends FormRequest {

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
            'user_reporting_id' => 'required',
            'user_reported_id' => 'required',
            'status' => 'required',
		];
	}
}
