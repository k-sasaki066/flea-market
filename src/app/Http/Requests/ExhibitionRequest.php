<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'=>['required'],
            'description'=>['required', 'max:255'],
            'image_url' => ['required','mimes:jpeg,png'],
            'category'=>['required'],
            'condition_id'=>['required'],
            'price'=>['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'商品名を入力してください',
            'description.required'=>'商品の説明を入力してください',
            'description.max'=>'商品の説明は255文字以下で入力してください',
            'image_url.required'=>'商品の画像を選択してください',
            'image_url.mimes'=>'「.png」または「.jpeg」形式でアップロードしてください',
            'category.required'=>'商品のカテゴリーを入力してください',
            'condition_id.required'=>'商品の状態を入力してください',
            'price.required'=>'商品の価格を入力してください',
            'price.numeric'=>'商品の価格は半角数字でカンマ( , )を抜いて入力してください',
            'price.min'=>'商品の価格は0円以上で入力してください',
        ];
    }
}
