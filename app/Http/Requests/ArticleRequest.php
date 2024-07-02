<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Models\Company;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|max:255',
            'company_id' => 'required|numeric',
            'price' => 'required|integer',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_name' => 'required|string|max:255',
            
            
        ];
    }
    public function prepareForValidation()
    {
    $this->merge([
        'company_id' => Company::where('company_name', $this->company_name)->firstOrFail()->id,
    ]);
    }
   
}
