<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'attribute_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'bill_id' => 'required|integer|exists:bills,id',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Tên s?n ph?m là b?t bu?c.',
            'product_name.string' => 'Tên s?n ph?m ph?i là chu?i k? t?.',
            'product_name.max' => 'Tên s?n ph?m không ðý?c vý?t quá 255 k? t?.',
            'attribute_name.required' => 'Tên thu?c tính là b?t bu?c.',
            'attribute_name.string' => 'Tên thu?c tính ph?i là chu?i k? t?.',
            'attribute_name.max' => 'Tên thu?c tính không ðý?c vý?t quá 255 k? t?.',
            'price.required' => 'Giá là b?t bu?c.',
            'price.numeric' => 'Giá ph?i là s?.',
            'price.min' => 'Giá không ðý?c nh? hõn 0.',
            'quantity.required' => 'S? lý?ng là b?t bu?c.',
            'quantity.integer' => 'S? lý?ng ph?i là s? nguyên.',
            'quantity.min' => 'S? lý?ng không ðý?c nh? hõn 1.',
            'bill_id.required' => 'Bill ID là b?t bu?c.',
            'bill_id.integer' => 'Bill ID ph?i là s? nguyên.',
            'bill_id.exists' => 'Bill ID không t?n t?i.',
        ];
    }
}
