<?php

namespace App\Services;

use App\Models\User;

class UserService
{
     public function getAllUsers(){
         return User::query()->get();
     }

     public function getUserById($id){
         $user = User::find($id);
         if(!$user){
             return response()->json([
                 'code'=>401,
                'error'=>'Khong tim thay nguoi dung'
             ]);
         }else{
             return response()->json([
                'code'=>200,
                'message'=>'Xem nguoi dung thanh cong',
                'data'=>$user
             ]);
         }
     }

     public function createUser($data){
         return User::create($data);
     }

     public function updateUser($id,$data){
         $user = User::findOrFail($id);

         $user->update($data);

         return $user;
     }

     public function deleteUser($id){
         $user = User::findOrFail($id);

         $user->delete();

         return $user;
     }



}
