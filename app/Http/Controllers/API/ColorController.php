<?php
// app/Http/Controllers/SizeController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ColorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function index()
    {
        $colors = $this->colorService->getAllColorsWithValues();

        // Xử lý dữ liệu và trả về view hoặc JSON response
        return response()->json($colors);
    }
    public function store(Request $request)
    {
        $value = $request->input('value');

        try {
            $colorValue = $this->colorService->createColorValue($value);
            return response()->json(['color_value' => $colorValue], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $color = $this->colorService->destroyColor($id);
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
