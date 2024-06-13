<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use APIResponse;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|string|min:6|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status_code' => 422,
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Register successfully',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(AuthRequest $request)
    {

         if(!$token=auth()->attempt($request->validated())){
             return $this->responseBadRequest(null,'Sai email hoac mat khau');
         }
         $user = Auth::user();
         $token = $user->createToken('auth_token')->plainTextToken;
         return $this->responseSuccess('Dang nhap thanh cong', [
             'token' => $token,
             'data'=> $user,
         ]);

//        $credentials = $request->only('email', 'password');
//
//        if (!Auth::attempt($credentials)) {
//            return response()->json([
//                'message' => 'Nhập sai tên hoặc mật khẩu',
//                'status_code' => 401,
//            ], 401);
//        }
//
//        $user = User::where('email', $request['email'])->firstOrFail();
//        $token = $user->createToken('auth_token')->plainTextToken;
//
//        return response()->json([
//            'message' => 'Đăng nhập thành công',
//            'data'=> $user,
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//
//        ], 200);
    }

    public function logout(Request $request)
    {
        if($request->user()){
            $request->user()->currentAccessToken()->delete();
        }
        Session::flush();
        return $this->responseSuccess(
            'Dang xuat thanh cong',
        );

//        auth()->user()->tokens()->delete();
//
//        return response()->json([
//            'message' => 'Đã đăng xuất thành công',
//            'status_code' => 200,
//        ], 200);
    }
}
