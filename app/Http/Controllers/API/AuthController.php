<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Essa\APIToolKit\Api\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * Register api
     *
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Authentication
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password123
     * @bodyParam c_password string required The confirmation password. Example: password123
     * @bodyParam phone string optional The phone number of the user. Example: +1234567890
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "token": "1|abcdefghijklmnopqrstuvwxyz",
     *     "name": "John Doe"
     *   },
     *   "message": "User registered successfully."
     * }
     * 
     * @response 404 {
     *   "success": false,
     *   "message": "Validation Error.",
     *   "data": {
     *     "email": ["The email has already been taken."]
     *   }
     * }
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'phone' => 'nullable',
        ]);
        
        if($validator->fails()){
            return $this->responseUnprocessable('Validation Error.', $validator->errors());       
        }
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data = [
            'token' => $user->createToken('MyApp')->plainTextToken,
            'name' => $user->name
        ];
        
        return $this->responseCreated('User registered successfully.', $data);
    }

    /**
     * Login api
     *
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Authentication
     * @bodyParam email string required The email of the user. Example: john@example.com
     * @bodyParam password string required The password of the user. Example: password123
     * 
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "token": "1|abcdefghijklmnopqrstuvwxyz",
     *     "name": "John Doe"
     *   },
     *   "message": "User login successfully."
     * }
     * 
     * @response 401 {
     *   "success": false,
     *   "message": "Unauthorised.",
     *   "data": {
     *     "error": "Unauthorised"
     *   }
     * }
     * 
     * @authenticated
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $data = [
                'token' => $user->createToken('MyApp')->plainTextToken,
                'name' => $user->name
            ];
            
            return $this->responseSuccess('User login successfully.', $data);
        } else { 
            return $this->responseUnAuthenticated('Unauthorised.', 'Invalid credentials');
        } 
    }

    /**
     * Logout api
     * 
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Authentication
     * @response 200 {
     *   "success": true,
     *   "data": [],
     *   "message": "User logged out successfully."
     * }
     * 
     * @authenticated
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        
        return $this->responseSuccess('User logged out successfully.', []);
    }

    /**
     * Get user profile
     * 
     * @param Request $request
     * @return JsonResponse
     * 
     * @group Authentication
     * @response 200 {
     *   "success": true,
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "email_verified_at": null,
     *     "created_at": "2023-01-01T00:00:00.000000Z",
     *     "updated_at": "2023-01-01T00:00:00.000000Z"
     *   },
     *   "message": "User profile retrieved successfully."
     * }
     * 
     * @authenticated
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return $this->responseSuccess('User profile retrieved successfully.', $user);
    }
}
