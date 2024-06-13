<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BillDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.product_name' => 'required|string|max:255',
            'data.*.attribute_name' => 'required|string|max:255',
            'data.*.price' => 'required|numeric|min:0',
            'data.*.quantity' => 'required|integer|min:1',
            'data.*.bill_id' => 'required|integer|exists:bills,id',
            'data.*.voucher' => 'nullable|string|max:50'
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'D? li?u là b?t bu?c.',
            'data.array' => 'D? li?u ph?i là m?t m?ng.',
            'data.*.product_name.required' => 'Tên s?n ph?m là b?t bu?c.',
            'data.*.product_name.string' => 'Tên s?n ph?m ph?i là chu?i k? t?.',
            'data.*.product_name.max' => 'Tên s?n ph?m không ðý?c vý?t quá 255 k? t?.',
            'data.*.attribute_name.required' => 'Tên thu?c tính là b?t bu?c.',
            'data.*.attribute_name.string' => 'Tên thu?c tính ph?i là chu?i k? t?.',
            'data.*.attribute_name.max' => 'Tên thu?c tính không ðý?c vý?t quá 255 k? t?.',
            'data.*.price.required' => 'Giá là b?t bu?c.',
            'data.*.price.numeric' => 'Giá ph?i là s?.',
            'data.*.price.min' => 'Giá không ðý?c nh? hõn 0.',
            'data.*.quantity.required' => 'S? lý?ng là b?t bu?c.',
            'data.*.quantity.integer' => 'S? lý?ng ph?i là s? nguyên.',
            'data.*.quantity.min' => 'S? lý?ng không ðý?c nh? hõn 1.',
            'data.*.bill_id.required' => 'ID hóa ðõn là b?t bu?c.',
            'data.*.bill_id.integer' => 'ID hóa ðõn ph?i là s? nguyên.',
            'data.*.bill_id.exists' => 'ID hóa ðõn không t?n t?i.',
            'data.*.voucher.string' => 'M? gi?m giá ph?i là chu?i k? t?.',
            'data.*.voucher.max' => 'M? gi?m giá không ðý?c vý?t quá 50 k? t?.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'error' => $errors,
                'status_code' => 402,
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
