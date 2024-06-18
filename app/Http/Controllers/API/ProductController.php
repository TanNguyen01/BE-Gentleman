<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Services\ProductService;
use App\Traits\APIResponse;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    use APIResponse;

    protected $productService;


    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        return $this->responseSuccess(
            __('Lấy danh sách thành công!'),
            [
                'product' => $products,
            ]
        );
    }

    public function store(ProductsRequest $request)
    {
        $productData = $request->all();
        $variantsData = $request->input('variants', []);

        // Xử lý tệp ảnh trong các biến thể
        foreach ($variantsData as &$variantData) {
            if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $variantData['image'] = $variantData['image']->store('image', 'public'); // Lưu ảnh vào thư mục 'storage/app/public/variant_images'
            }
        }

        // Gọi phương thức lưu trữ sản phẩm và biến thể
        $product = $this->productService->storeProductVariants($productData, $variantsData);

        return $this->responseCreated(
            __('Tao san pham thanh cong!'),
            [
                'product' => $product
            ]
        );
    }


    public function show(int $id)
    {
        $product = $this->productService->showProduct($id);
        if (!$product) {
            return
                $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay san pham!')

                );
        } else {
            return $this->responseSuccess(
                __('lay san pham thanh cong!'),
                [
                    'product' => $product,
                ]
            );
        }
    }

    public function update(ProductsRequest $request, $id)
    {
        $data = $request->all();
        $product = $this->productService->updateProduct($id, $data);
        if (!$product) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay san pham !')
            );
        } else {
            $product->update($data);
            return $this->responseSuccess(
                __('sua thanh cong'),
                [
                    'product' => $product
                ]
            );
        }
    }

    public function destroy(string $id)
    {
        $product = $this->productService->destroyProduct($id);
        if (!$product) {
            return $this->responseNotFound(
                Response::HTTP_NOT_FOUND,
                __('khong tim thay bien the!')
            );
        } else {
            return $this->responseDeleted(null, Response::HTTP_NO_CONTENT);
        }
    }
}
