<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request) {
        $formFields = $request->validate([
            'material_type' => 'required',
            'status' => 'required',
            'total_price' => 'required'
        ]);

        $formFields['user_id'] = auth()->id();
        
        $order = Order::create($formFields);
        return array('order_id' => $order['id'], 'success' => true);
    }
}
