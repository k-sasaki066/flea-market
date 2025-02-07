<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'way' => ['required'],
            'post_cord' => ['required'],
            'address' => ['required'],
            'building' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'way.required' => '支払い方法を選択してください',
            'post_cord.required' => '郵便番号を設定してください',
            'address.required' => '住所を設定してください',
            'building.required' => '番地や建物名を設定してください',
        ];
    }
}
