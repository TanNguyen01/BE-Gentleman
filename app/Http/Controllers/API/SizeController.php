<?php
// app/Http/Controllers/SizeController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\SizeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SizeController extends Controller
{
    protected $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    public function index()
    {
        $sizes = $this->sizeService->getAllSizesWithValues();

        // Xử lý dữ liệu và trả về view hoặc JSON response
        return response()->json($sizes);
    }
    public function store(Request $request)
    {
        $value = $request->input('value');

        try {
            $sizeValue = $this->sizeService->createSizeValue($value);
            return response()->json(['size_value' => $sizeValue], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $size = $this->sizeService->destroySize($id);
        if (!$size) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay size!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
