<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required',
            'weight' => 'required',
            'description' => 'required',
            'product_prices' => 'required|array',
            'product_prices.*.min_qty' => 'required',
            'product_prices.*.max_qty' => 'required',
            'product_prices.*.price' => 'required',
        ];
    }
}
