<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\TransactionEvent;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\MercadoPagoTransaction;

class MercadoPagoController extends Controller
{
    protected $mercadopagoBaseUrl = 'https://api.mercadopago.com/v1';
    
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . config('services.mercadopago.token'),
            'Content-Type' => 'application/json',
            'X-Idempotency-Key' => (string) Str::uuid(),
        ];
    }
    
    /**
     * Create a new order in MercadoPago
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);
        
        try {

            $user = auth('sanctum')->user();
            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
        
            
            // Calculate total amount
            $totalAmount = 0;
            $items = [];
            
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;
                
                
                $items[] = [
                    'title' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'currency_id' => 'MXN',
                ];
            }
            
            $externalReference = Str::uuid().'-' . Str::random(8);
            
            if( env('APP_ENV') == 'local' ) {
                $totalAmount = 5;
            }
            
            $payload = [
                    'type' => 'point',
                    'external_reference' => $externalReference,
                    'transactions' => [
                        'payments' => [
                        [
                            'amount' => number_format($totalAmount, 2, '.', '')
                        ]
                    ]
                ],
                'config' => [
                    'point' => [
                        'terminal_id' => config('services.mercadopago.terminal_id'),
                        'print_on_terminal' => 'no_ticket'
                    ]
                ],
                'description' => 'Pago en tienda - ' . config('app.name'),
                'integration_data' => [
                    'platform_id' => '1234567890',
                    'integrator_id' => 'dev_24c65fb163bf11ea96500242ac130004'
                ]
            ];
            
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->mercadopagoBaseUrl}/orders", $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Save transaction to database
                $transaction = MercadoPagoTransaction::create([
                    'order_id' => $data['id'] ?? null,
                    'type' => $data['type'] ?? null,
                    'user_id' => $user->id,
                    'external_reference' => $externalReference,
                    'description' => $data['description'] ?? null,
                    'processing_mode' => $data['processing_mode'] ?? null,
                    'country_code' => $data['country_code'] ?? null,
                    'integration_data' => isset($data['integration_data']) ? json_encode($data['integration_data']) : null,
                    'status' => $data['status'] ?? 'created',
                    'status_detail' => $data['status_detail'] ?? null,
                    'config' => isset($data['config']) ? json_encode($data['config']) : null,
                    'transactions' => isset($data['transactions']) ? json_encode($data['transactions']) : null,
                    'taxes' => isset($data['taxes']) ? json_encode($data['taxes']) : null,
                    'amount' => $totalAmount,
                    'payment_id' => $data['transactions']['payments'][0]['id'] ?? null,
                    'payment_status' => $data['transactions']['payments'][0]['status'] ?? null,
                ]);
                
                //broadcast(new TransactionEvent($user, $user, 'Pago realizado', $transaction));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'data' => $transaction
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order in MercadoPago',
                'error' => $response->json()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('MercadoPago order creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get order status
     *
     * @param  string  $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderStatus($orderId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->mercadopagoBaseUrl}/orders/{$orderId}");
                
            if ($response->successful()) {
                $data = $response->json();
                
                // Update transaction status in database
                $transaction = MercadoPagoTransaction::where('order_id', $orderId)->first();
                if ($transaction) {
                    $transaction->update([
                        'status' => $data['status'] ?? $transaction->status,
                        'status_detail' => $data['status_detail'] ?? $transaction->status_detail,
                        'payment_status' => $data['transactions']['payments'][0]['status'] ?? $transaction->payment_status,
                        'config' => isset($data['config']) ? json_encode($data['config']) : $transaction->config,
                        'transactions' => isset($data['transactions']) ? json_encode($data['transactions']) : $transaction->transactions,
                        'taxes' => isset($data['taxes']) ? json_encode($data['taxes']) : $transaction->taxes,
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch order status',
                'error' => $response->json()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('MercadoPago order status error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching order status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}