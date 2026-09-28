<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer.user', 'farmer', 'market', 'items', 'pickupSlot']);

        if ($search = $request->query('search')) {
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('farmer', fn($f) => $f->where('stall_name', 'like', "%{$search}%"));
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($marketId = $request->query('market_id')) {
            $query->where('market_id', $marketId);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $markets = Market::where('status', 'active')->orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'markets'));
    }

    public function show(Order $order)
    {
        $order->load(['customer.user', 'farmer.user', 'market', 'items.product', 'pickupSlot', 'reviews']);
        return view('admin.orders.show', compact('order'));
    }
}
