<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderDetailRequest extends FormRequest
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
            'data.*.variant_id' => 'required|integer|exists:variants,id', // Kiem tra variant_id co ton tai trong bang variants khong
            'data.*.order_id' => 'required|integer|exists:orders,id', // Kiem tra order_id co ton tai trong bang orders khong
            'data.*.status' => 'required|string|in:pending,shipped,delivered,canceled', // Trang thai la bat buoc va phai la mot trong cac gia tri: pending, shipped, delivered, canceled
            'data.*.quantity' => 'required|integer|min:1', // So luong la bat buoc, phai la so nguyen va khong nho hon 1
            'data.*.voucher_id' => 'nullable|integer|exists:vouchers,id', // ID ma giam gia la tuy chon, phai la so nguyen va ton tai trong bang vouchers
        ];
    }

    public function messages()
    {
        return [
            'data.required' => 'Du lieu la bat buoc.',
            'data.array' => 'Du lieu phai la mot mang.',
            'data.*.variant_id.required' => 'ID bien the la bat buoc.',
            'data.*.variant_id.integer' => 'ID bien the phai la so nguyen.',
            'data.*.variant_id.exists' => 'ID bien the khong ton tai.',
            'data.*.order_id.required' => 'ID don hang la bat buoc.',
            'data.*.order_id.integer' => 'ID don hang phai la so nguyen.',
            'data.*.order_id.exists' => 'ID don hang khong ton tai.',
            'data.*.status.required' => 'Trang thai don hang la bat buoc.',
            'data.*.status.string' => 'Trang thai don hang phai la chuoi ky tu.',
            'data.*.status.in' => 'Trang thai don hang khong hop le.',
            'data.*.quantity.required' => 'So luong la bat buoc.',
            'data.*.quantity.integer' => 'So luong phai la so nguyen.',
            'data.*.quantity.min' => 'So luong khong duoc nho hon 1.',
            'data.*.voucher_id.integer' => 'ID ma giam gia phai la so nguyen.',
            'data.*.voucher_id.exists' => 'ID ma giam gia khong ton tai.',
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
