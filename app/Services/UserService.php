<?php

namespace App\Services;

use App\Models\User;

class UserService extends AbstractServices
{
    private $user;
    function __construct(User $user)
    {
        parent::__construct($user);
    }
    public function getAllUsers()
    {
        return $this->eloquentGetAll();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::find($id);
// dd($user);
        if ($user) {
            $user->update($data);
        }
        return $user;
    }

    public function deleteUser($id)
    {
        //  $user = User::find($id);

        // if($user){
        //   if($user->image){
        //   Storage::disk('images_user')->delete($user->image);
        //  }
        //  $user->delete();
        //  }

        //  return $user;
    }




}
