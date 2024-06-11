<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VariantResource;
use App\Services\VariantService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    use ApiResponseTrait;

    protected $variantService;

    public function __construct(VariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function index()
    {
        try {
            $variants = $this->variantService->getAllVariants();
            return $this->successResponse(['variants' => VariantResource::collection($variants)],'Get variant list successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $variant = $this->variantService->createVariant($request->all());
            return new VariantResource($variant);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function show($id)
    {
        try {
            $variant = $this->variantService->getVariantById($id);
            return new VariantResource($variant);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $variant = $this->variantService->getVariantById($id);
            $variant = $this->variantService->updateVariant($variant, $request->all());
            return new VariantResource($variant);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function destroy($id)
    {
        try {
            $this->variantService->deleteVariant($id);
            return $this->successResponse("Variant deleted successfully");
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
