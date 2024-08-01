<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    use APIResponse;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUsers();
        return $this->responseSuccess('Lay danh sach nguoi dung thanh cong', ['data' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['role'] = 1;
        $user = $this->userService->createUser($data);
        return $this->responseCreated(
            'them thanh cong',
            [
                'data' => $user,
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return $this->responseNotFound(
                'Khong tim thay nguoi dung nay',
                Response::HTTP_NOT_FOUND
            );
        }
        return $this->responseSuccess(
            'Xem thong tin nguoi dung thanh cong',
            ['data' => $user]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = $this->userService->updateUser($id, $request->all());
        if (!$user) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                'Khong tim thay nguoi dung'

            );
        }
        return $this->responseSuccess(
            'Cap nhay thanh cong',
            ['data' => $user]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //  $user = $this->userService->deleteUser($id);
        //  if(!$user){
        //   return $this->responseNotFound(
        //     Response::HTTP_NOT_FOUND,
        //    'Khong tim thay nguoi dung'
        //   );
        //  }
        //  return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
    }
}