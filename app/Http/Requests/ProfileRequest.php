<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ProfileRequest extends FormRequest
{
    protected $_user;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->_user = Auth::user();

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'profile.first_name' => 'required|string',
            'profile.last_name' => 'required|string',
            'profile.email' => 'required|email|unique:users,email,'.$this->_user->id,
            'profile.phone' => 'required|regex:/\+?[0-9\-]+/i',
        ];
    }

    public function messages()
    {
        return [
            'required' => __('Mandatory field'),
            'email' => __('Wrong email format'),
            'profile.phone.regex' => __('Wrong phone format'),
            'profile.email.unique' => __('This email already registered'),
        ];
    }
}
