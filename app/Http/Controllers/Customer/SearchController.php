<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->input('q', ''));
        $type  = $request->input('type', '');
        $minPrice = $request->input('min_price');

        $products = collect();
        $farmers  = collect();
        $markets  = collect();

        if (!empty($query)) {
            if (empty($type) || $type === 'products') {
                $pq = Product::where('availability_status', '!=', 'sold_out')
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['farmer', 'category']);

                if ($minPrice !== null && $minPrice !== '') {
                    $pq->where('price', '>=', (float) $minPrice);
                }

                $products = $pq->paginate(8)->withQueryString();
            }

            if (empty($type) || $type === 'farmers') {
                $farmers = Farmer::where('approval_status', 'approved')
                    ->where(function ($q) use ($query) {
                        $q->where('stall_name', 'like', "%{$query}%")
                          ->orWhere('bio', 'like', "%{$query}%")
                          ->orWhere('product_types', 'like', "%{$query}%");
                    })
                    ->with(['markets'])
                    ->take(6)
                    ->get();
            }

            if (empty($type) || $type === 'markets') {
                $markets = Market::where('status', 'active')
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('location', 'like', "%{$query}%")
                          ->orWhere('address', 'like', "%{$query}%");
                    })
                    ->take(6)
                    ->get();
            }
        }

        return view('customer.search', compact('query', 'products', 'farmers', 'markets'));
    }
}
