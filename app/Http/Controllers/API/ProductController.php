<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsRequest;
use App\Services\ColorService;
use App\Services\ProductService;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
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
        // Gọi phương thức lưu trữ sản phẩm và biến thể
        $product = $this->productService->createProductWithVariantsAndAttributes($productData, $variantsData);

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
        $product = $this->productService->updateProductWithVariantsAndAttributes($id, $data);
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

    public function getBySale()
    {
        try {
            $products = $this->productService->getAllWithSale();

            if ($products->isEmpty()) {
                return $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay san pham!')
                );
            } else {
                return response()->json([
                    'data' => $products,
                    'message' => 'Successfully retrieved products with sale_id',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->responseServerError(
                __('loi khi lay danh sach san pham theo sale_id: ') . $e->getMessage()
            );
        }
    }
    public function getProductBySaleId($id)
    {
        try {
            $products = $this->productService->getProductBySaleId($id);

            if ($products->isEmpty()) {
                return $this->responseNotFound(
                    Response::HTTP_NOT_FOUND,
                    __('khong tim thay san pham!')
                );
            } else {
                return response()->json([
                    'data' => $products,
                    'message' => 'Successfully retrieved products with sale_id',
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            return $this->responseServerError(
                __('loi khi lay danh sach san pham theo sale_id: ') . $e->getMessage()
            );
        }
    }





    public function getByColor(){
        try{
            $products = $this->productService->getAllWithColor();

            if ($products->isEmpty()) {
                return $this->responseNotFound(
                    __('khong tim thay san pham!')
                );
            }else{
                return response()->json([
                    'data' => $products,
                    'message' => 'Successfully retrieved products with price',
                    Response::HTTP_OK
                ]);
            }
        }catch (\Exception $e){
            return $this->responseServerError(
                __('loi khi lay danh sach san pham theo mau'),
            );
        }
    }

    public function getProductByColor(Request $request){
        try{
            $value = $request->input('color');
            $color = $this->productService->getColorById($value);
//            if ($products->isEmpty()) {
//                return $this->responseNotFound(
//                    __('khong tim thay san pham!')
//                );
//            }else{
//                return response()->json([
//                    'data' => $products,
//                    'message' => 'Successfully retrieved products with price',
//                    Response::HTTP_OK
//                ]);
//            }
            return response()->json(
                  $color
            );
        }catch (\Exception $e){
            return $this->responseServerError(
                __('loi khi lay danh sach san pham theo mau'),
            );
        }
    }


}
