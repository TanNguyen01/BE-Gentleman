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
            'voucher' => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Ten san pham la bat buoc.',
            'product_name.string' => 'Ten san pham phai la chuoi ky tu.',
            'product_name.max' => 'Ten san pham khong duoc vuot qua 255 ky tu.',
            'attribute_name.required' => 'Ten thuoc tinh la bat buoc.',
            'attribute_name.string' => 'Ten thuoc tinh phai la chuoi ky tu.',
            'attribute_name.max' => 'Ten thuoc tinh khong duoc vuot qua 255 ky tu.',
            'price.required' => 'Gia la bat buoc.',
            'price.numeric' => 'Gia phai la so.',
            'price.min' => 'Gia khong duoc nho hon 0.',
            'quantity.required' => 'So luong la bat buoc.',
            'quantity.integer' => 'So luong phai la so nguyen.',
            'quantity.min' => 'So luong khong duoc nho hon 1.',
            'bill_id.required' => 'Bill ID la bat buoc.',
            'bill_id.integer' => 'Bill ID phai la so nguyen.',
            'bill_id.exists' => 'Bill ID khong ton tai.',
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
