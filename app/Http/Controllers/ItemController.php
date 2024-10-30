<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index() {
        // return Item::all();
        $items = Item::all();
        
        $items->map(function($vendor) {
            if (!str_starts_with($vendor->img, 'http')) {
                $vendor->img = Storage::disk('public')->url($vendor->img);
                
            }
            return $vendor;
        });
        
        return $items;
    }
}
