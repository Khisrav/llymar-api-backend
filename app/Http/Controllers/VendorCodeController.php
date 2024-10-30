<?php

namespace App\Http\Controllers;

use App\Models\VendorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorCodeController extends Controller
{
    public function index()
    {
        $vendors = VendorCode::all();
        
        $vendors->map(function($vendor) {
            if (!str_starts_with($vendor->img, 'http')) {
                $vendor->img = Storage::disk('public')->url($vendor->img);
                
            }
            return $vendor;
        });
        
        return $vendors;
    }
}
