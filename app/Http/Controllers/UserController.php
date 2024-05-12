<?php

namespace App\Http\Controllers;

// use App\Models\User;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        return Auth::user();
    }

    public function update(Request $request) {
        $user_id = Auth::id();
        //update user data
        
        $user = User::findOrFail($user_id);

        $validate = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'company' => '',
            'phone' => 'required|max:255'
        ]);

        $user->fill($validate);
        $user->save();

        return true;
    }

    public function history() {
        $user_id = Auth::id();
    
        $orders = Order::where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->get();
    
        return $orders;
    }
}
