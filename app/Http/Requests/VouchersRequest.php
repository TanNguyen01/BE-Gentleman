<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class VouchersRequest extends FormRequest
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
    public function rules(): array
    {
        return
            [
                'voucher_code' => 'required|string|max:255',
                'discount_amount' => 'required|numeric|min:0',
                'expiration_date' => 'required|date|after:today',
                'minimum_purchase' => 'required|numeric|min:0',
                'usage_limit' => 'required|integer|min:0',
                'status' => 'required',
                'description' => 'nullable|string',
            ];
    }

    public function messages(): array
    {

        return [
            'voucher_code.required' => 'Voucher code is required.',
            'discount_amount.required' => 'Discount amount is required.',
            'discount_amount.numeric' => 'Discount amount must be a number.',
            'discount_amount.min' => 'Discount amount must be at least :min.',
            'expiration_date.required' => 'Expiration date is required.',
            'expiration_date.date' => 'Expiration date must be a valid date.',
            'expiration_date.after' => 'Expiration date must be after today.',
            'minimum_purchase.required' => 'Minimum purchase is required.',
            'minimum_purchase.numeric' => 'Minimum purchase must be a number.',
            'minimum_purchase.min' => 'Minimum purchase must be at least :min.',
            'usage_limit.required' => 'Usage limit is required.',
            'usage_limit.integer' => 'Usage limit must be an integer.',
            'usage_limit.min' => 'Usage limit must be at least :min.',
            'status.required' => 'Status is required.',
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
