<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Facades\MercadoPago;
use App\Models\MercadoPagoNotification;
use App\Models\MercadoPagoTransaction;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Log the raw request for debugging
        Log::info('MercadoPago Webhook Received:', [
            'query' => $request->query(),
            'payload' => $request->all()
        ]);
    
        try {
            // Verify the request is from MercadoPago
            if (!$this->isValidRequest($request)) {
                Log::warning('Invalid webhook request received', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            // Get the resource ID from either the query string or the request body
            $resourceId = $request->input('data.id', $request->query('id'));
            $type = $request->input('type', $request->query('topic'));
    
            if (!$resourceId) {
                Log::warning('No resource ID in webhook', $request->all());
                return response()->json(['error' => 'No resource ID provided'], 400);
            }
    
            // Save the notification
            $notification = $this->saveNotification($request);
    
            // Process based on type
            switch ($type) {
                case 'payment':
                    return $this->handlePayment($resourceId, $notification);
                case 'point_integration_wh':
                    return $this->handlePointIntegration($resourceId, $notification);
                // Add other cases as needed
                default:
                    Log::info("Unhandled webhook type: {$type}", ['resource_id' => $resourceId]);
                    return response()->json(['status' => 'unhandled_type']);
            }
    
        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    

    protected function isValidRequest(Request $request): bool
    {
        // Add any IP whitelisting or signature verification here
        return true;
    }

    protected function saveNotification(Request $request): MercadoPagoNotification
    {
        return MercadoPagoNotification::create([
            'notification_id' => $request->input('id', $request->query('id')),
            'type' => $request->input('type', $request->query('topic')),
            'live_mode' => $request->boolean('live_mode', $request->query('live_mode', false)),
            'action' => $request->input('action', ''),
            'api_version' => $request->input('api_version', 'v1'),
            'user_id' => $request->input('user_id', ''),
            'resource_id' => $request->input('data.id', $request->query('id')),
            'status' => $request->input('data.status', ''),
            'data' => array_merge($request->all(), [
                'query_params' => $request->query()
            ]),
        ]);
    }

    protected function handlePayment(string $paymentId, MercadoPagoNotification $notification)
    {
        try {
            // For test transactions, return a success response
            if ($paymentId === '123456') {
                Log::info('Test payment received', ['payment_id' => $paymentId]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Test payment processed successfully'
                ]);
            }
            // For real transactions, use the MercadoPago API
            $accessToken = config('services.mercadopago.token');
            if (empty($accessToken)) {
                Log::error('MercadoPago access token is not set');
                return response()->json(['error' => 'Server configuration error'], 500);
            }
            
            MercadoPago::config()->setAccessToken($accessToken);
            $client = MercadoPago::payment_client();
            $payment = $client->get($paymentId);
    
            if (!$payment) {
                Log::error('Payment not found in MercadoPago', ['payment_id' => $paymentId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }
    
            // Process real payment...
            $transaction = MercadoPagoTransaction::updateOrCreate(
                ['payment_id' => $paymentId],
                [
                    'status' => $payment->status ?? null,
                    'status_detail' => $payment->status_detail ?? null,
                    'payment_status' => $payment->status ?? null,
                    'transactions' => json_encode($payment, JSON_PRETTY_PRINT),
                ]
            );
    
            $this->handlePaymentStatus($transaction, $payment);
            return response()->json(['status' => 'success']);
    
        } catch (\Exception $e) {
            Log::error('Error processing payment webhook: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error processing payment'], 500);
        }
    }

    protected function handlePointIntegration(string $paymentIntentId, MercadoPagoNotification $notification)
    {
        try {
            // Get payment intent details using the new SDK
            MercadoPago::config()->setAccessToken(config('services.mercadopago.token'));
            $client = MercadoPago::point_client();
            $paymentIntent = $client->get($paymentIntentId);
    
            if (!$paymentIntent) {
                Log::error('Failed to fetch payment intent', [
                    'payment_intent_id' => $paymentIntentId
                ]);
                return response()->json(['error' => 'Failed to fetch payment intent'], 500);
            }
    
            Log::info('Payment intent details:', (array) $paymentIntent);
    
            // Update the transaction if payment_id is available
            if (!empty($paymentIntent->payment_id)) {
                $transaction = MercadoPagoTransaction::where('payment_id', $paymentIntent->payment_id)->first();
                
                if ($transaction) {
                    $transaction->update([
                        'status' => $paymentIntent->status ?? null,
                        'status_detail' => $paymentIntent->status_detail ?? null,
                        'payment_status' => $paymentIntent->status ?? null,
                        'transactions' => json_encode($paymentIntent, JSON_PRETTY_PRINT),
                    ]);
    
                    $this->handlePaymentStatus($transaction, $paymentIntent);
                }
            }
    
            return response()->json(['status' => 'success']);
    
        } catch (\Exception $e) {
            Log::error('Error processing point integration webhook: ' . $e->getMessage(), [
                'payment_intent_id' => $paymentIntentId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error processing point integration'], 500);
        }
    }
    protected function handlePaymentStatus($transaction, $paymentData)
    {
        $status = $paymentData->status ?? $paymentData['status'] ?? null;
        $statusDetail = $paymentData->status_detail ?? $paymentData['status_detail'] ?? null;
        
        if (!$status) {
            Log::warning('No status found in payment data', ['payment_id' => $transaction->payment_id]);
            return;
        }
    
        Log::info("Handling payment status", [
            'transaction_id' => $transaction->id,
            'payment_id' => $transaction->payment_id,
            'status' => $status,
            'status_detail' => $statusDetail
        ]);
    
        switch ($status) {
            case 'created':
                $this->handleCreatedStatus($transaction, $paymentData);
                break;
                
            case 'at_terminal':
                $this->handleAtTerminalStatus($transaction, $paymentData);
                break;
                
            case 'processed':
                $this->handleProcessedStatus($transaction, $paymentData);
                break;
                
            case 'canceled':
                $this->handleCanceledStatus($transaction, $paymentData);
                break;
                
            case 'failed':
                $this->handleFailedStatus($transaction, $paymentData, $statusDetail);
                break;
                
            case 'refunded':
                $this->handleRefundedStatus($transaction, $paymentData);
                break;
                
            case 'action_required':
                $this->handleActionRequiredStatus($transaction, $paymentData);
                break;
                
            default:
                Log::warning("Unhandled payment status", [
                    'status' => $status,
                    'status_detail' => $statusDetail,
                    'payment_id' => $transaction->payment_id
                ]);
                break;
        }
    }
    
    // Add these new handler methods to your controller
    protected function handleCreatedStatus($transaction, $paymentData)
    {
        Log::info("Payment created", ['payment_id' => $transaction->payment_id]);
        // Add any specific logic for created status
    }
    
    protected function handleAtTerminalStatus($transaction, $paymentData)
    {
        Log::info("Payment at terminal", ['payment_id' => $transaction->payment_id]);
        // Add any specific logic for at_terminal status
    }
    
    protected function handleProcessedStatus($transaction, $paymentData)
    {
        Log::info("Payment processed successfully", ['payment_id' => $transaction->payment_id]);
        // Add any specific logic for processed status
        // Example: Send confirmation email, update order status, etc.
    }
    
    protected function handleCanceledStatus($transaction, $paymentData)
    {
        $reason = $paymentData->cancellation_reason ?? $paymentData['cancellation_reason'] ?? 'unknown';
        Log::info("Payment canceled", [
            'payment_id' => $transaction->payment_id,
            'reason' => $reason
        ]);
        // Add any specific logic for canceled status
    }
    
    protected function handleFailedStatus($transaction, $paymentData, $statusDetail)
    {
        Log::error("Payment failed", [
            'payment_id' => $transaction->payment_id,
            'status_detail' => $statusDetail,
            'payment_data' => $paymentData
        ]);
        // Add any specific logic for failed status
        // Example: Notify admin, log detailed error, etc.
    }
    
    protected function handleRefundedStatus($transaction, $paymentData)
    {
        $refundAmount = $paymentData->refunded_amount ?? $paymentData['refunded_amount'] ?? 0;
        Log::info("Payment refunded", [
            'payment_id' => $transaction->payment_id,
            'amount' => $refundAmount
        ]);
        // Add any specific logic for refunded status
    }
    
    protected function handleActionRequiredStatus($transaction, $paymentData)
    {
        Log::info("Action required for payment", ['payment_id' => $transaction->payment_id]);
        // Add any specific logic for action_required status
        // Example: Notify admin to check the terminal
    }
}