<?php

namespace App\Http\Controllers;

use App\Models\VendorCode;
use Illuminate\Http\Request;

class VendorCodeController extends Controller
{
    public function index()
    {
        return VendorCode::all();
    }
}
