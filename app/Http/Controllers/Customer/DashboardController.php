<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $customer = $user->customer;

        if (!$customer) {
            $customer = $user->customer()->create(['preferences' => []]);
        }

        $activeOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', [Order::STATUS_PLACED, Order::STATUS_ACCEPTED, Order::STATUS_READY_FOR_PICKUP])
            ->with(['farmer', 'market', 'items', 'pickupSlot'])
            ->orderBy('pickup_date')
            ->get();

        $completedOrdersCount = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->count();

        $totalSpent = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total');

        $favoriteFarmers = $customer->favoriteFarmers()
            ->with(['markets', 'products'])
            ->take(4)
            ->get();

        $favoriteProducts = $customer->favoriteProducts()
            ->with(['farmer', 'category', 'images'])
            ->take(4)
            ->get();

        $recommendedProducts = Product::where('availability_status', '!=', 'sold_out')
            ->whereNotIn('id', $customer->favoriteProducts()->pluck('products.id'))
            ->with(['farmer', 'images'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        $nearbyMarkets = Market::where('status', 'active')
            ->withCount(['farmers' => function ($q) {
                $q->where('approval_status', 'approved');
            }])
            ->take(3)
            ->get();

        $notifications = $user->notifications()->take(5)->get();

        $recentOrders = Order::where('customer_id', $customer->id)
            ->with(['farmer', 'market', 'items', 'orderItems', 'pickupSlot'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => Order::where('customer_id', $customer->id)->count(),
            'pending_orders' => Order::where('customer_id', $customer->id)->whereIn('status', [Order::STATUS_PLACED, Order::STATUS_ACCEPTED])->count(),
            'favorites' => $customer->favoriteProducts()->count() + $customer->favoriteFarmers()->count(),
            'total_spent' => (float) $totalSpent,
        ];

        $upcomingPickups = Order::where('customer_id', $customer->id)
            ->whereIn('status', [Order::STATUS_PLACED, Order::STATUS_ACCEPTED, Order::STATUS_READY_FOR_PICKUP])
            ->whereDate('pickup_date', '>=', now()->toDateString())
            ->with(['farmer', 'pickupSlot'])
            ->orderBy('pickup_date')
            ->take(4)
            ->get();

        $favorites = $favoriteProducts->map(fn($p) => (object)['product' => $p]);

        return view('customer.dashboard', compact(
            'stats',
            'recentOrders',
            'activeOrders',
            'upcomingPickups',
            'completedOrdersCount',
            'totalSpent',
            'favoriteFarmers',
            'favoriteProducts',
            'favorites',
            'recommendedProducts',
            'nearbyMarkets',
            'notifications'
        ));
    }
}
