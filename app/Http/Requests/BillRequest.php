<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class BillRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'user_id' => 'required|integer|exists:users,id',
        'recipient_phone' => 'required|string|max:15|',
        'recipient_address' => 'required|string|max:255',
        'total_amount' => 'numeric|min:0',
        'status' => 'required',
        'pay' => 'string'
    ];
}

public function messages()
{
    return [
        'user_id.required' => 'User ID la bat buoc.',
        'user_id.integer' => 'User ID phai la so nguyen.',
        'user_id.exists' => 'User ID khong ton tai.',
        'recipient_phone.required' => 'So dien thoai nguoi nhan la bat buoc.',
        'recipient_phone.string' => 'So dien thoai nguoi nhan phai la chuoi ky tu.',
        'recipient_phone.max' => 'So dien thoai nguoi nhan khong duoc vuot qua 15 ky tu.',
        'recipient_address.required' => 'Dia chi nguoi nhan la bat buoc.',
        'recipient_address.string' => 'Dia chi nguoi nhan phai la chuoi ky tu.',
        'recipient_address.max' => 'Dia chi nguoi nhan khong duoc vuot qua 255 ky tu.',
        'total_amount.required' => 'Tong so tien la bat buoc.',
        'total_amount.numeric' => 'Tong so tien phai la so.',
        'total_amount.min' => 'Tong so tien khong duoc nho hon 0.',
        'bill_date.required' => 'Ngay hoa don la bat buoc.',
        'bill_date.date_format' => 'Ngay hoa don khong dung dinh dang YYYY-MM-DD.',
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
