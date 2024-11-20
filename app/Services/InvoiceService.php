<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    protected $apiClientID;
    protected $apiUrl;
    protected $apiJWT;
    protected $customerCode;
    protected $accountId;

    public function __construct()
    {
        $this->apiClientID = config('services.tochka.api_client_id');
        $this->apiJWT = config('services.tochka.api_jwt');
        $this->apiUrl = config('services.tochka.api_url');
        $this->customerCode = env('CUSTOMER_CODE');
        $this->accountId = env('ACCOUNT_ID');
    }

    public function createInvoice($order)
    {
        $invoiceDate = Carbon::now()->format('Y-m-d'); // Текущая дата
        $expiryDate = Carbon::now()->addDays(7)->format('Y-m-d'); // Дата окончания действия счёта (пример)

        try {
            // Сформируем запрос для API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiJWT,
                'Content-Type' => 'application/json'
            ])->post("{$this->apiUrl}/invoice/v1.0/bills", [
                'Data' => [
                    'customerCode' => $this->customerCode,
                    'accountId' => $this->accountId,
                    'Content' => [
                        'Invoice' => [
                            'number' => $order->id,
                            'basedOn' => 'Основание платежа', // Замените на нужное значение
                            'comment' => $order->comment ?? 'Комментарий',
                            'paymentExpiryDate' => $expiryDate,
                            'date' => $invoiceDate,
                            'totalAmount' => $order->total_price,
                            // 'totalNds' => '1', // Замените на нужное значение
                            'Positions' => [
                                [
                                    'positionName' => 'Название товара или услуги', // Замените на реальное название
                                    'unitCode' => 'шт.', // Единица измерения
                                    'ndsKind' => 'nds_0', // НДС
                                    'price' => $order->total_price,
                                    'quantity' => 1, // Количество
                                    'totalAmount' => $order->total_price,
                                    // 'totalNds' => '1', // Замените на нужное значение
                                ]
                            ]
                        ]
                    ],
                    'SecondSide' => [
                        // 'accountId' => '00000000000000000000/000000000', // Заполните согласно требованиям
                        // 'legalAddress' => '197183, г. Санкт-Петербург, ул. Сестрорецкая, д. 8, литер а, помещ. 29н, офис 12',
                        // 'kpp' => '000000000',
                        // 'bankName' => 'ТОЧКА ПАО БАНКА "ФК ОТКРЫТИЕ"',
                        // 'bankCorrAccount' => '00000000000000000000',
                        'taxCode' => '0000000000',
                        'type' => 'company',
                        // 'secondSideName' => 'ООО "ГОС-АЛЬЯНС"'
                    ]
                ]
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            // Логируем ошибку при создании счёта
            Log::error("Failed to create invoice", [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return ['error' => 'Unable to create invoice'];
        }
    }
    
    public function sendInvoicePdf($documentId, $email)
    {
        $url = "{$this->apiUrl}/invoice/v1.0/bills/{$this->customerCode}/{$documentId}/file";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiJWT
        ])->get($url, []);

        if (!$response->successful()) {
            Log::error('Failed to fetch invoice PDF', [
                'document_id' => $documentId,
                'error' => $response->body()
            ]);
            return response()->json(['success' => false, 'error' => 'Failed to fetch invoice PDF.'], 500);
        }

        $pdf = $response->body();
        
        try {
            Mail::send([], [], function ($message) use ($email, $pdf) {
                $message->to($email)
                        ->subject('Счет на оплату LLYMAR.RU')
                        ->attachData($pdf, 'invoice.pdf', [
                            'mime' => 'application/pdf',
                        ])
                        ->text('PDF счет во вложении.');
            });

            // Log or handle success
            Log::info('Mail sent successfully to ' . $email);
        } catch (\Exception $e) {
            Log::error('Failed to send mail: ' . $e->getMessage());
            Log::error($e->getTraceAsString()); // Add detailed trace
            DB::rollBack(); // Откат транзакции в случае ошибки отправки email
            return response()->json(['success' => false, 'error' => 'Failed to send email.'], 500);
        }
        // $url = "{$this->apiUrl}/invoice/v1.0/bills/{$this->customerCode}/{$documentId}/email";
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $this->apiClientID
        // ])->post($url, [
        //     "Data" => [
        //         "email" => $email
        //     ]
        // ]);
                    
        // if ($response->successful()) {
        //     return $response->json();
        // }
    
        // return json_encode(['success' => false]);
    }
    
    //check invoice status
    public function checkInvoiceStatus($documentId)
    {
        $url = "{$this->apiUrl}/invoice/v1.0/bills/{$this->customerCode}/{$documentId}/payment-status";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiJWT
        ])->get($url);
                    
        if ($response->successful()) {
            return $response->json();
        }
    
        return json_encode(['success' => false]);
    }
}
