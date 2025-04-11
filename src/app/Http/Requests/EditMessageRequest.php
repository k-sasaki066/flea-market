<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditMessageRequest extends FormRequest
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
            'message_send'=>['required', 'max:400'],
        ];
    }

    public function messages()
    {
        return [
            'message_send.required'=>'本文を入力してください',
            'message_send.max'=>'本文は400文字以内で入力してください',
        ];
    }
}
