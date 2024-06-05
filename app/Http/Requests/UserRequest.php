<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class UserRequest extends FormRequest
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
        if (request()->isMethod('POST')) {
            return [
                'email' => 'required|email|unique:users,email',
                'name' => 'nullable|string',
                'password' => 'nullable|string',
                'role' => 'nullable|integer',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'address' => 'nullable|string',
                'phone' => 'nullable|string',
                'status' => [
                    "required",
                    Rule::in([
                        User::Enable,
                        User::Disable,
                    ])
                ]
            ];
        }else{
            return [
                'email' => 'nullable|email|unique:users,email',
                'name' => 'nullable|string',
                'password' => 'nullable|string',
                'role' => 'nullable|integer|in:0,1',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'address' => 'nullable|string',
                'phone' => 'nullable|string',
                'status' => [
                    "required",
                    Rule::in([
                        User::Enable,
                        User::Disable,
                    ])
                ]
            ];
        }
    }

    public function messages(): array{

        return [
            'email.unique' => 'Email da ton tai',
            'email.required' => 'Email khong duoc de trong',
            'email.email' => 'Email khong hop le',
            'password.required' => 'Password khong duoc de trong',
            'password.confirmed' => 'Nhap lai passsword',
            'name.required' => 'Ten khong duoc de trong',
            'name.string' => 'Ten khong hop le',
            'avatar.mimes' => 'Anh phai co dinh dang jpg,png,gif,svg',
           // 'phone.required' => 'Sdt khong duoc de trong',
            'phone.string' => 'Sdt khong hop le',
          //  'address.required' => 'Dia chi khong duoc de trong',
            'address.string' => 'Dia chi ko hop le',


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


