<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'ticket_price_out_hours'    => 'required|numeric|integer|min:0',
            'ticket_price_in_hours'    => 'required|numeric|integer|min:0',
        ];
    }

    public function attributes()
    {
        return [
            'ticket_price_out_hours'    => 'Giá vé ngoài giờ hành chính',
            'ticket_price_in_hours'    => 'Giá vé thường',
        ];
    }
}
