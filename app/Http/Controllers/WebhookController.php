<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $entityBody = $request->getContent();

        $json_key = '{"kty":"RSA","e":"AQAB","n":"rwm77av7GIttq-JF1itEgLCGEZW_zz16RlUQVYlLbJtyRSu61fCec_rroP6PxjXU2uLzUOaGaLgAPeUZAJrGuVp9nryKgbZceHckdHDYgJd9TsdJ1MYUsXaOb9joN9vmsCscBx1lwSlFQyNQsHUsrjuDk-opf6RCuazRQ9gkoDCX70HV8WBMFoVm-YWQKJHZEaIQxg_DU4gMFyKRkDGKsYKA0POL-UgWA1qkg6nHY5BOMKaqxbc5ky87muWB5nNk4mfmsckyFv9j1gBiXLKekA_y4UwG2o1pbOLpJS3bP_c95rm4M9ZBmGXqfOQhbjz8z-s9C11i-jmOQ2ByohS-ST3E5sqBzIsxxrxyQDTw--bZNhzpbciyYW4GfkkqyeYoOPd_84jPTBDKQXssvj8ZOj2XboS77tvEO1n1WlwUzh8HPCJod5_fEgSXuozpJtOggXBv0C2ps7yXlDZf-7Jar0UYc_NJEHJF-xShlqd6Q3sVL02PhSCM-ibn9DN9BKmD"}';
        $jwks = json_decode($json_key, true, 512, JSON_THROW_ON_ERROR);

        try {
            $decoded = JWT::decode($entityBody, JWK::parseKey($jwks, "RS256"));
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Invalid webhook'], 400);
        }

        // Тело вебхука
        $decoded_array = (array) $decoded;

        // Обработка данных вебхука
        $this->processWebhook($decoded_array);

        return response()->json(['status' => 'success'], 200);
    }
    
    protected function getOrderNumber($inputString) {
        // Define the regular expression pattern to match the order number
        $pattern = '/^([0-9]+-[0-9A-Za-z]+)/';
    
        // Perform the regular expression match
        if (preg_match($pattern, $inputString, $matches)) {
            // Return the first match (the order number)
            return $matches[1];
        }
    
        // Return null if no match is found
        return null;
    }

    protected function processWebhook(array $decoded)
    {
        Log::info($decoded);
        
        $webhookType = $decoded['webhookType'] ?? null;
        $order_number = $this->getOrderNumber($decoded['purpose']);
        
        if ($webhookType === 'incomingPayment' && $order_number) {
            //set order status to paid
            $order = Order::where([
                ['id', '=',substr($order_number, 2)],
                ['status', '=', 'pending'],
                ['document_id', '=', $decoded['documentId']],
                ['total_price', '=', $decoded['amount']]
            ])->first();
            if ($order) {
                $order->status = 'paid';
                $order->save();
            }
        }
    }
}
