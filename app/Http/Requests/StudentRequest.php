<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'email'  => 'required|email',
            'birth_day' => 'required',
            'gender'    => 'required',
            'mobile'    => 'required|numeric',
            'passport'  => 'required|numeric',
            'passport_date'  => 'required',
            'passport_address'  => 'required',
            'course_id' => 'required',
            'program_id' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'  => 'Tên học viên',
            'email'  => 'Email',
            'birth_day' => 'Ngày sinh',
            'gender'    => 'Giới tính',
            'mobile'    => 'Số điện thoai',
            'passport'  => 'Số CMT/căn cước',
            'passport_date'  => 'Ngày cấp CMT/căn cước',
            'passport_address'  => 'Nơi cấp CMT/căn cước',
            'source_id' => 'Nguồn tuyển sinh',
            'course_id' => 'Khóa học',
            'program_id' => 'Chương trình đào tạo',
        ];
    }
}
