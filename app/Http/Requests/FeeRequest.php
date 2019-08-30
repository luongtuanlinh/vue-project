<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeeRequest extends FormRequest
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
            'class_id'  => 'required',
            'program_id'  => 'required',
            'fee'  => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'class_id'  => 'Hạng giấy phép',
            'program_id'  => 'Chương trình đào tạo',
            'fee'  => 'Học phí',
        ];
    }
}
