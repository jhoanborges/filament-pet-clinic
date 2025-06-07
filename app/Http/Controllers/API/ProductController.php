<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Essa\APIToolKit\Api\ApiResponse;

class ProductController extends Controller
{
    use ApiResponse;

    /**
     * Get all products
     * 
     * @return JsonResponse
     * 
     * @group Products
     * @response 200 {
     *   "success": true,
     *   "message": "Products retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product Name",
     *       "description": "Product Description",
     *       "price": 19.99,
     *       "stock": 100,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'clinic'])->get();
        return $this->responseSuccess('Products retrieved successfully', $products);
    }

    /**
     * Get a specific product by ID
     * 
     * @param int $id
     * @return JsonResponse
     * 
     * @group Products
     * @urlParam id integer required The ID of the product. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Product retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Product Name",
     *     "description": "Product Description",
     *     "price": 19.99,
     *     "stock": 100,
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z",
     *     "category": {
     *       "id": 1,
     *       "name": "Category Name"
     *     },
     *     "clinic": {
     *       "id": 1,
     *       "name": "Clinic Name"
     *     }
     *   }
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Product not found",
     *   "data": null
     * }
     */
    public function show($id): JsonResponse
    {
        $product = Product::with(['category', 'clinic'])->find($id);
        
        if (!$product) {
            return $this->responseNotFound('Product not found', null);
        }
        
        return $this->responseSuccess('Product retrieved successfully', $product);
    }

    /**
     * Get products by category
     * 
     * @param int $categoryId
     * @return JsonResponse
     * 
     * @group Products
     * @urlParam categoryId integer required The ID of the category. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Products by category retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product Name",
     *       "description": "Product Description",
     *       "price": 19.99,
     *       "stock": 100,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function getByCategory($categoryId): JsonResponse
    {
        $products = Product::with(['category', 'clinic'])
            ->where('category_id', $categoryId)
            ->get();
            
        return $this->responseSuccess('Products by category retrieved successfully', $products);
    }

    /**
     * Get products by clinic
     * 
     * @param int $clinicId
     * @return JsonResponse
     * 
     * @group Products
     * @urlParam clinicId integer required The ID of the clinic. Example: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Products by clinic retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product Name",
     *       "description": "Product Description",
     *       "price": 19.99,
     *       "stock": 100,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function getByClinic($clinicId): JsonResponse
    {
        $products = Product::with(['category', 'clinic'])
            ->where('clinic_id', $clinicId)
            ->get();
            
        return $this->responseSuccess('Products by clinic retrieved successfully', $products);
    }
}
