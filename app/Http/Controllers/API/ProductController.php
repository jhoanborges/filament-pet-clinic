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
     * Get paginated products
     * 
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Products
     * @queryParam per_page int Items per page. Default: 16
     * @queryParam page int Page number. Default: 1
     * 
     * @response 200 {
     *   "success": true,
     *   "message": "Products retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Product Name",
     *       "description": "Product Description",
     *       "price": 19.99,
     *       "image_url": "https://pet-clinic.hexagun.mx/products_demo_images/cats/1.jpg",
     *       "stock": 100,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 5,
     *     "per_page": 16,
     *     "to": 16,
     *     "total": 80
     *   }
     * }
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'clinic'])
            ->orderBy('id', 'desc')
            ->get();
            
        return $this->responseSuccess(
            'Products retrieved successfully',
            $products,
        );
    }
    
    /**
     * Get infinite scroll products with search functionality
     * 
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Products
     * @queryParam per_page int Items per page. Default: 10
     * @queryParam page int Page number. Default: 1
     * @queryParam search string Search term to filter products by name or description
     * 
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Blue Buffalo Digestive Care Cat Food",
     *       "description": "Premium cat food for digestive health",
     *       "price": 19.99,
     *       "image_url": "https://pet-clinic.hexagun.mx/products_demo_images/cats/1.jpg",
     *       "stock": 100,
     *       "created_at": "2023-01-01T00:00:00.000000Z",
     *       "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "last_page": 1,
     *     "per_page": 10,
     *     "total": 1,
     *     "search_term": "blue buffalo"
     *   }
     * }
     */
    public function infiniteScroll(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 100);
        $page = $request->input('page', 1);
        $searchTerm = $request->input('search');
        
        $query = Product::with(['category', 'clinic'])
            ->when($searchTerm, function($query) use ($searchTerm) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            })
            ->orderBy('created_at', 'desc');
            
        $products = $query->paginate($perPage, ['*'], 'page', $page);
            
        return response()->json([
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'search_term' => $searchTerm
            ]
        ]);
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
