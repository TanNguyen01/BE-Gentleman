<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:225|unique:users',
            'password' => 'required|string',
            'address'=> 'nullable|string',
             'phone'=>'nullable|string',
             'role' => 'nullable|integer|in:0,1',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address'=>$request->address,
                'phone'=>$request->phone,
                'role'=> $request->role,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                  'code'=>401,
                   'message'=>'Nhap sai ten hoac mat khau'
            ]);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
           'message'=>'Dang nhap thanh cong',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'code'=>200,
            'message'=>'Da dang xuat thanh cong'
        ]);
    }


}
