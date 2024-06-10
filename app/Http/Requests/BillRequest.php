<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'recipient_phone' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
            'recipient_address' => 'required|string|max:255',
            'voucher' => 'nullable|string|max:50',
            'total_amount' => 'required|numeric|min:0',
            'bill_date' => 'required|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID là b?t bu?c.',
            'user_id.integer' => 'User ID ph?i là s? nguyên.',
            'user_id.exists' => 'User ID không t?n t?i.',
            'recipient_phone.required' => 'S? ði?n tho?i ngý?i nh?n là b?t bu?c.',
            'recipient_phone.string' => 'S? ði?n tho?i ngý?i nh?n ph?i là chu?i k? t?.',
            'recipient_phone.max' => 'S? ði?n tho?i ngý?i nh?n không ðý?c vý?t quá 15 k? t?.',
            'recipient_phone.regex' => 'S? ði?n tho?i ngý?i nh?n không h?p l?.',
            'recipient_address.required' => 'Ð?a ch? ngý?i nh?n là b?t bu?c.',
            'recipient_address.string' => 'Ð?a ch? ngý?i nh?n ph?i là chu?i k? t?.',
            'recipient_address.max' => 'Ð?a ch? ngý?i nh?n không ðý?c vý?t quá 255 k? t?.',
            'voucher.string' => 'Voucher ph?i là chu?i k? t?.',
            'voucher.max' => 'Voucher không ðý?c vý?t quá 50 k? t?.',
            'total_amount.required' => 'T?ng s? ti?n là b?t bu?c.',
            'total_amount.numeric' => 'T?ng s? ti?n ph?i là s?.',
            'total_amount.min' => 'T?ng s? ti?n không ðý?c nh? hõn 0.',
            'bill_date.required' => 'Ngày hóa ðõn là b?t bu?c.',
            'bill_date.date_format' => 'Ngày hóa ðõn không ðúng ð?nh d?ng YYYY-MM-DD.',
        ];
    }
}
