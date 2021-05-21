<?php

namespace App\Modules\Admin\Requests;

use App\Helpers\FileHelper;
use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest {

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
            'city_id' => 'required',
            'building_id' => 'required',
            'hall_blueprint_id' => 'required',
            'date' => 'required|date_format:Y-m-d H:i|after_or_equal:today',
            'free_pass_logo' => 'sometimes|file|max:2048|mimetypes:' . FileHelper::mimesImage(),
            'event_image' => 'sometimes|file|max:2048|mimetypes:' . FileHelper::mimesImage(),
            'prices.*.price' => 'numeric|min:0|max:100000',
            'priceGroups.*.discount' => 'int|min:0|max:100',
		];
	}

	public function messages()
    {
        return array_merge(parent::messages(), [
            'date.after_or_equal' => __('Date must be greater or equal to current date'),
        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'city_id' => __('City'),
            'building_id' => __('Building'),
            'hall_blueprint_id' => __('Hall'),
            'prices.*.price' => __('Price'),
            'priceGroups.*.discount' => __('Discount'),
        ]);
    }
}
