<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'time_order'    => 'required',
            'expire_date'    => 'required',
            'user_id'    => 'required',
            'course_id'    => 'required',
            'quantity'    => 'required|integer|gt:0',
        ];
    }

    public function attributes()
    {
        return [
            'time_order'    => 'Thời gian yêu cầu',
            'user_id'    => 'Người yêu cầu',
            'course_id'    => 'Khóa học',
            'quantity'    => 'Số lượng học viên',
        ];
    }
}
