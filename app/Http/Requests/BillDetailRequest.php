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
            'data.required' => 'Du lieu la bat buoc.',
            'data.array' => 'Du lieu phai la mot mang.',
            'data.*.product_name.required' => 'Ten san pham la bat buoc.',
            'data.*.product_name.string' => 'Ten san pham phai la chuoi ky tu.',
            'data.*.product_name.max' => 'Ten san pham khong duoc vuot qua 255 ky tu.',
            'data.*.attribute_name.required' => 'Ten thuoc tinh la bat buoc.',
            'data.*.attribute_name.string' => 'Ten thuoc tinh phai la chuoi ky tu.',
            'data.*.attribute_name.max' => 'Ten thuoc tinh khong duoc vuot qua 255 ky tu.',
            'data.*.price.required' => 'Gia la bat buoc.',
            'data.*.price.numeric' => 'Gia phai la so.',
            'data.*.price.min' => 'Gia khong duoc nho hon 0.',
            'data.*.quantity.required' => 'So luong la bat buoc.',
            'data.*.quantity.integer' => 'So luong phai la so nguyen.',
            'data.*.quantity.min' => 'So luong khong duoc nho hon 1.',
            'data.*.bill_id.required' => 'ID hoa don la bat buoc.',
            'data.*.bill_id.integer' => 'ID hoa don phai la so nguyen.',
            'data.*.bill_id.exists' => 'ID hoa don khong ton tai.',
            'data.*.voucher.string' => 'Ma giam gia phai la chuoi ky tu.',
            'data.*.voucher.max' => 'Ma giam gia khong duoc vuot qua 50 ky tu.',
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
