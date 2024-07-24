<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Traits\APIResponse;
use Illuminate\Http\Response;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class PostController extends Controller
{
    use APIResponse;
    use ApiResponseTrait;
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        try {
            $categories = $this->postService->getAllPost();
            return $this->successResponse([
                'categories' => $categories,
            ], 'Get All Categories');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $request = $request->all();
        $data = $this->postService->storePost($request);
        if($data == true){
            return $this->responseCreated(
                __('tao danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
        else{
            return $this->responseCreated(
                __('tao danh muc khong thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function show(string $id)
    {
        $data = $this->postService->showPost($id);
        if (!$data) {
            return $this->APIError(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay danh muc')
            );
        } else {
            return $this->responseSuccess(
                __('hien thi danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
    }

    public function update($id,Request $request)
    {
        $request = $request->all();
        $data = $this->postService->updatePost($id,$request);
        if($data == true){
            return $this->responseCreated(
                __('tao danh muc thanh cong'),
                [
                    'data' => $data,
                ]
            );
        }
        else{
            return $this->responseCreated(
                __('tao danh muc khong thanh cong'),
                [
                     'data' => $data,
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $color = $this->postService->destroyPost($id);
        if (!$color) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay color!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }

}
