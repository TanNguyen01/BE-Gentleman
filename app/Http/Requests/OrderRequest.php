<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id', // Ki?m tra user_id có t?n t?i trong b?ng users không
            'total_amount' => 'required|numeric|min:0', // S? ti?n ph?i là s? và không nh? hõn 0
            'status' => 'required|string|in:pending,completed,canceled', // Tr?ng thái là b?t bu?c và ph?i là m?t trong các giá tr?: pending, completed, canceled
            'order_date' => 'required|date_format:Y-m-d', // Ngày ph?i ðúng ð?nh d?ng YYYY-MM-DD
            'voucher_id' => 'nullable|integer|exists:vouchers,id', // Voucher không b?t bu?c, ph?i là s? nguyên và t?n t?i trong b?ng vouchers
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID là b?t bu?c.',
            'user_id.integer' => 'User ID ph?i là s? nguyên.',
            'user_id.exists' => 'User ID không t?n t?i.',
            'total_amount.required' => 'T?ng s? ti?n là b?t bu?c.',
            'total_amount.numeric' => 'T?ng s? ti?n ph?i là s?.',
            'total_amount.min' => 'T?ng s? ti?n không ðý?c nh? hõn 0.',
            'status.required' => 'Tr?ng thái ðõn hàng là b?t bu?c.',
            'status.string' => 'Tr?ng thái ðõn hàng ph?i là chu?i k? t?.',
            'status.in' => 'Tr?ng thái ðõn hàng không h?p l?.',
            'order_date.required' => 'Ngày ð?t hàng là b?t bu?c.',
            'order_date.date_format' => 'Ngày ð?t hàng không ðúng ð?nh d?ng YYYY-MM-DD.',
            'voucher_id.integer' => 'Voucher ID ph?i là s? nguyên.',
            'voucher_id.exists' => 'Voucher ID không t?n t?i.',
        ];
    }
}
