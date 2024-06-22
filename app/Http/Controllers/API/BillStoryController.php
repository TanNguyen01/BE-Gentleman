<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\BillStoryRequest;
use App\Services\BillStoryService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class BillStoryController extends BillStoryService
{
    use APIResponse;
    protected $billService;
    function __construct(BillStoryService $billService)
    {
        $this->billService = $billService;
    }

    public function index()
    {
        $billStories = $this->billService->getAllBillStory();
        return response()->json($billStories);
    }

    public function store(BillStoryRequest $request)
    {
        $request = $request->validated();
        $billStory = $this->billService->storeBillStory($request);
        return $this->responseCreated(__
        ('tao danh muc thanh cong'),
        [
            'status' => $billStory,
        ]);
    }

    public function show(string $id)
    {
       $data = $this->billService->showBillStory($id);
       if (!$data) {
        return $this->responseNotFound(
            Response::HTTP_NOT_FOUND,
            __('khong tim thay danh muc'));
        }else{
        return $this->responseSuccess(
            __('hien thi danh muc thanh cong'),
          [
              'data' => $data,
          ]
        );
        }
    }
}
