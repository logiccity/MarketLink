<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'markets_count' => Market::where('status', 'active')->count(),
            'farmers_count' => Farmer::where('approval_status', 'approved')->count(),
            'products_count' => Product::where('availability_status', '!=', 'sold_out')->count(),
        ];

        return view('about', compact('stats'));
    }
}
