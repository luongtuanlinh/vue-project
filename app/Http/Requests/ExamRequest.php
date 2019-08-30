<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
            'type'  => 'required',
            'code'  => 'required|unique:exams,code,'.$this->id,
            'class_id'=> 'required',
            'name'  => 'required',
            'date'  => 'required',
            'fee'   => 'required|numeric|gt:0'
        ];
    }

    public function attributes()
    {
        return [
            'type'  => 'loại lớp thi',
            'class_id'  => 'hạng xe',
            'code'  => 'mã lớp thi',
            'name'  => 'tên lớp thi',
            'date'  => 'ngày thi',
            'fee'   => 'lệ phí thi'
        ];
    }
}
