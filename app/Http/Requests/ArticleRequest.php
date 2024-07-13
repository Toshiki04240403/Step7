<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'company_id' => 'required|numeric',
            'product_name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'comment' => 'nullable|max:1000',
            'img_path' => 'nullable|image|max:2048'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.required' => '会社IDは必須です',
            'company_id.numeric' => '会社IDは数値で入力してください',
            'product_name.required' => '商品名は必須です',
            'product_name.max' => '商品名は255文字以内で入力してください',
            'price.required' => '価格は必須です',
            'price.numeric' => '価格は数値で入力してください',
            'price.min' => '価格は0以上の値を入力してください',
            'stock.required' => '在庫数は必須です',
            'stock.integer' => '在庫数は整数で入力してください',
            'stock.min' => '在庫数は0以上の値を入力してください',
            'comment.max' => 'コメントは1000文字以内で入力してください',
            'img_path.image' => '画像ファイルを選択してください',
            'img_path.max' => '画像ファイルのサイズは2MB以内にしてください'
        ];
    }
}
