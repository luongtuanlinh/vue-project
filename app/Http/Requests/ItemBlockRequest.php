<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemBlockRequest extends FormRequest
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
            'type'  => 'required_without:item_id',
            'item_id'   => 'required_without:type',
            'date'   => 'required',
            'start_time'   => 'required',
            'end_time'   => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'type'  => 'Hạng xe',
            'item_id'   => 'xe',
            'date'   => 'ngày khóa',
            'start_time'   => 'thời gian bắt đầu',
            'end_time'   => 'thời gian kết thúc',
        ];
    }
}
