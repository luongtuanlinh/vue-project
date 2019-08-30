<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemTypePriceRequest extends FormRequest
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
            'price' => 'required',
            'price_type' => 'required',
            'time_type' => 'required',
            'item_type_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'item_type_id'  => 'Tên hạng xe',
            'price_type' => 'Tên loại hình',
            'time_type' => 'Tên loại thời gian',
            'price' => "Giá",
        ];
    }
}
