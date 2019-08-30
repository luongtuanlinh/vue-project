<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'max'   => 'required|integer|min:1',
            'car_class_id' => 'required',
            'status'    => 'required',
            'start_time'    => 'required',
            'end_time'    => 'required',
            'exam_time'    => 'required',
            'exam_end_time'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name'  => 'Tên khóa học',
            'max'   => 'Sỹ số tối đa',
            'car_class_id' => 'Hạng xe',
            'status'    => 'Trạng thái',
            'start_time'    => 'Ngày khai giảng',
            'end_time'    => 'Ngày bế giảng',
            'exam_time'    => 'Ngày thi sát hạch',
            'exam_end_time'    => 'Ngày thi tốt nghiệp',
        ];
    }
}
