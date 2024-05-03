<?php

namespace App\Http\Controllers;

use App\Models\Additional;
use App\Models\Opening;
use App\Models\Order;
use App\Models\VendorAmount;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request) {
        $formFields = $request->validate([
            'material_type' => 'required',
            'status' => '',
            'total_price' => 'required',
            'openings' => 'required',
            'vendor_codes' => 'required',
            'additionals' => ''
        ]);

        $formFields['user_id'] = auth()->id();
        
        $order = Order::create(array(
            'material_type' => $formFields['material_type'],
            'status' => 'pending',
            'total_price' => $formFields['total_price'],
            'user_id' => $formFields['user_id'],
        ));

        $openings = $formFields['openings'];

        foreach ($openings as $opening) {
            Opening::create([
                'order_id' => $order['id'],
                'name' => $opening['name'],
                'type' => $opening['type'],
                'doors' => $opening['doors'],
                'width' => $opening['width'],
                'height' => $opening['height']
            ]);
        }

        $additionals = $formFields['additionals'];
        foreach ($additionals as $additional) {
            Additional::create([
                'order_id' => $order['id'],
                'item_id' => $additional['id'],
                'amount' => $additional['amount'],
                'price' => $additional['price']
            ]);
        }

        // $temp = [];
        // foreach ($request['vendor_codes'] as $vc) {
        //     $temp[] = $vc;
        // }
        // return $temp;

        $vendorAmounts = $formFields['vendor_codes'];
        foreach ($vendorAmounts as $vendorAmount) {
            VendorAmount::create([
                'order_id' => $order['id'],
                'vendor_code_id' => $vendorAmount['id'],
                'amount' => $vendorAmount['amount'],
                'price' => $vendorAmount['price']
            ]);
        }

        return array(
            'success' => true, 
            'order_id' => $order['id']
        );
    }

    public function index(Request $request) {
        return Order::all();
    }
}
