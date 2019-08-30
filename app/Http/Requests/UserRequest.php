<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name'  => 'required',
            'phone'  => 'required|numeric|unique:users,phone,'. $this->id,
            'email'  => 'required|email|unique:users,email,'. $this->id,
        ];
    }

    public function attributes()
    {
        return [
            'name'  => 'Tên',
            'phone'  => 'Số điện thoại',
            'email'  => 'Email',
        ];
    }
}
