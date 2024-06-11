<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id', // Ki?m tra user_id có t?n t?i trong b?ng users không
            'total_amount' => 'required|numeric|min:0', // S? ti?n ph?i là s? và không nh? hõn 0
            'status' => 'required|string|in:pending,completed,canceled', // Tr?ng thái là b?t bu?c và ph?i là m?t trong các giá tr?: pending, completed, canceled
            'order_date' => 'required|date_format:Y-m-d', // Ngày ph?i ðúng ð?nh d?ng YYYY-MM-DD
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'User ID la bat buoc.',
            'user_id.integer' => 'User ID phai la so nguyen.',
            'user_id.exists' => 'User ID khong ton tai.',
            'total_amount.required' => 'Tong so tien la bat buoc.',
            'total_amount.numeric' => 'Tong so tien phai la so.',
            'total_amount.min' => 'Tong so tien khong duoc nho hon 0.',
            'status.required' => 'Trang thai don hang la bat buoc.',
            'status.string' => 'Trang thai don hang phai la chuoi ky tu.',
            'status.in' => 'Trang thai don hang khong hop le.',
            'order_date.required' => 'Ngay dat hang la bat buoc.',
            'order_date.date_format' => 'Ngay dat hang khong dung dinh dang YYYY-MM-DD.',
            'voucher_id.integer' => 'Voucher ID phai la so nguyen.',
            'voucher_id.exists' => 'Voucher ID khong ton tai.',
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
