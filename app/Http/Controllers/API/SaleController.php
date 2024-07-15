<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Services\SaleService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class SaleController extends Controller
{
    use ApiResponseTrait;

    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index()
    {
        try {
            $sale = $this->saleService->getAllSale();
            return $this->successResponse([
                'sales' => $sale,
            ], 'Get All Categories');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:categories',
            ]);

            $sale = $this->saleService->eloquentPostCreate($data);
            return $this->successResponse(new SaleResource($sale), 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->saleService->showSale($id);
            return $this->successResponse(new SaleResource($category));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            $category = $this->saleService->updateSale($id, $data);
            return $this->successResponse(new SaleResource($category));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->saleService->eloquentDelete($id);
            return $this->successResponse('Category deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getOnlayout()
    {
        try {
            $sale = $this->saleService->onLayoutSale();
            return $this->successResponse([
                'sales' => $sale,
            ], 'Get All sale onlayout');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function saleWithProduct($id){
        try {
            $sale = $this->saleService->EloquentSaleWithProduct($id);
            return $this->successResponse([
                'sales' => $sale,
            ], 'Get All product find sale');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }
}
