<?php

namespace App\Http\Requests;

use App\Models\Variant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VariantsRequest extends FormRequest
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
        return [
            "price" => "required|integer|min:0",
            "price_promotional" => "required|integer|min:0",
            "quantity" => "required|integer|min:0",
            "status" => [
                "required",
                Rule::in([
                    Variant::Enable,
                    Variant::Disable,
                ])
            ],
            "description" => "required|string",
            "image" => "required|string",
        ];
    }

    public function messages(): array
    {

        return [
            "price.required" => "Nhap gia goc san pham",
            "price_promotional.required" => "Nhap gia khuyen mai san pham",
            "quantity.required" => "Nhap so luong",
            "status.required" => "Nhap trang thai san pham",
            "description.required" => "Nhap mieu ta san pham",
            "image.required" => "them anh san pham",
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
