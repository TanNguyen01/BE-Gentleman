<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderDetailRequest extends FormRequest
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
            'variant_id' => 'required|integer|exists:variants,id', // Ki?m tra variant_id có t?n t?i trong b?ng variants không
            'order_id' => 'required|integer|exists:orders,id', // Ki?m tra order_id có t?n t?i trong b?ng orders không
            'status' => 'required|string|in:pending,shipped,delivered,canceled', // Tr?ng thái là b?t bu?c và ph?i là m?t trong các giá tr?: pending, shipped, delivered, canceled
            'quantity' => 'required|integer|min:1', // S? lý?ng là b?t bu?c, ph?i là s? nguyên và không nh? hõn 1
        ];
    }

    public function messages()
    {
        return [
            'variant_id.required' => 'Variant ID là b?t bu?c.',
            'variant_id.integer' => 'Variant ID ph?i là s? nguyên.',
            'variant_id.exists' => 'Variant ID không t?n t?i.',
            'order_id.required' => 'Order ID là b?t bu?c.',
            'order_id.integer' => 'Order ID ph?i là s? nguyên.',
            'order_id.exists' => 'Order ID không t?n t?i.',
            'status.required' => 'Tr?ng thái ðõn hàng là b?t bu?c.',
            'status.string' => 'Tr?ng thái ðõn hàng ph?i là chu?i k? t?.',
            'status.in' => 'Tr?ng thái ðõn hàng không h?p l?.',
            'quantity.required' => 'S? lý?ng là b?t bu?c.',
            'quantity.integer' => 'S? lý?ng ph?i là s? nguyên.',
            'quantity.min' => 'S? lý?ng không ðý?c nh? hõn 1.',
        ];
    }
}
