<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Opening;
use App\Models\Additional;
use App\Models\VendorAmount;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function store(Request $request)
    {
        $formFields = $this->validateRequest($request);
        $user = auth()->user();
        $formFields['user_id'] = $user->id;

        DB::beginTransaction();

        try {
            $order = $this->createOrder($formFields);
            $this->createOpenings($order, $formFields['openings']);
            $this->createAdditionals($order, $formFields['additionals']);
            $this->createVendorAmounts($order, $formFields['vendor_codes']);

            $invoice = $this->invoiceService->createInvoice($order, $user);
            $this->handleInvoiceCreation($invoice, $order);
            
            Log::info('Заказ успешно создан: ' . $order->document_id . ' (Invoice: ' . $invoice['Data']['documentId'] . ')');

            $emailResponse = $this->invoiceService->sendInvoicePdf($order->document_id, 'kh.khisrav2018@gmail.com');

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка при создании заказа: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json(['success' => false, 'error' => 'Не удалось создать заказ.'], 500);
        }
    }

    public function index(Request $request)
    {
        return Order::all();
    }

    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'status' => 'nullable',
            'total_price' => 'required',
            'openings' => 'required|array',
            'vendor_codes' => 'required|array',
            'additionals' => 'nullable|array',
            'comment' => 'nullable',
            'delivery' => 'nullable',
        ]);
    }

    protected function createOrder(array $formFields)
    {
        return Order::create([
            'status' => 'payment_waiting',
            'total_price' => $formFields['total_price'],
            'user_id' => $formFields['user_id'],
            'comment' => $formFields['comment'] ?? '',
            'delivery' => $formFields['delivery'] ?? '',
        ]);
    }

    protected function createOpenings(Order $order, array $openings)
    {
        foreach ($openings as $opening) {
            Opening::create([
                'order_id' => $order->id,
                'name' => $opening['name'],
                'type' => $opening['type'],
                'doors' => $opening['doors'],
                'width' => $opening['width'],
                'height' => $opening['height']
            ]);
        }
    }

    protected function createAdditionals(Order $order, array $additionals)
    {
        foreach ($additionals as $additional) {
            Additional::create([
                'order_id' => $order->id,
                'item_id' => $additional['id'],
                'amount' => $additional['amount'],
                'price' => $additional['price'],
                'discount' => $additional['discount'],
            ]);
        }
    }

    protected function createVendorAmounts(Order $order, array $vendorCodes)
    {
        foreach ($vendorCodes as $vendorAmount) {
            VendorAmount::create([
                'order_id' => $order->id,
                'vendor_code_id' => $vendorAmount['id'],
                'amount' => $vendorAmount['amount'],
                'price' => $vendorAmount['price'],
                'discount' => $vendorAmount['discount'],
            ]);
        }
    }

    protected function handleInvoiceCreation(array $invoice, Order $order)
    {
        if (isset($invoice['code']) && $invoice['code'] !== '200') {
            Log::error('Ошибка при создании счета: ' . json_encode($invoice));
            DB::rollBack();
            throw new \Exception('Ошибка при создании счета: ' . $invoice['message']);
        }

        $order->document_id = $invoice['Data']['documentId'] ?? null;
        $order->save();
    }

    protected function handleEmailResponse(array $emailResponse)
    {
        if (isset($emailResponse['Data']['result']) && !$emailResponse['Data']['result']) {
            Log::error('Ошибка при отправке счета на почту: ' . json_encode($emailResponse));
            DB::rollBack();
            throw new \Exception('Ошибка при отправке счета на почту.');
        }
    }
}
