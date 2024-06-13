<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategorieRequest extends FormRequest
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
              "name" => "required|string",
              "quantity" => "integer",
              "status"=> [

                     Rule::in([
                        Category::Enable,
                        Category::Disable,
                    ])
              ]
        ];
    }

    public function messages(): array{

        return [
            "name.required" => "Nhap ten danh muc",
           // "quantity.required" => "Nhap so luong",
           // "status.required" => "Nhap trang thai danh muc",
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'error' => $errors,
                'status_code' => 402,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }


}
