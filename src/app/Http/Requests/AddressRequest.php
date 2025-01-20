<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'nickname' => ['required'],
            'post_cord' => ['required', 'regex:/^[0-9]{3}-[0-9]{4}\z/'],
            'address' => ['required'],
            'building' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'nickname.required' => 'ニックネームを入力してください',
            'post_cord.required' => '郵便番号を入力してください',
            'post_cord.regex' => '郵便番号は半角数字でハイフン(-)を入れて8文字で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '番地や建物名を入力してください',
        ];
    }
}
