<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $farmer = $user->farmer;

        if (!$farmer) {
            return redirect()->route('home')->with('error', 'Farmer profile not found.');
        }

        $totalOrders = Order::where('farmer_id', $farmer->id)->count();
        $pendingOrders = Order::where('farmer_id', $farmer->id)
            ->where('status', Order::STATUS_PLACED)
            ->with(['customer.user', 'items', 'orderItems'])
            ->latest()
            ->get();
        $readyOrders = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_READY_FOR_PICKUP)->count();
        $completedOrders = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_COMPLETED)->sum('total');

        $stats = [
            'new_orders' => $pendingOrders->count(),
            'active_products' => Product::where('farmer_id', $farmer->id)->where('availability_status', '!=', 'sold_out')->count(),
            'monthly_revenue' => (float) Order::where('farmer_id', $farmer->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereMonth('created_at', now()->month)
                ->sum('total'),
            'avg_rating' => (float) ($farmer->reviews()->where('status', 'approved')->avg('rating') ?: 5.0),
        ];

        $recentOrders = Order::where('farmer_id', $farmer->id)
            ->with(['customer.user', 'market', 'items', 'orderItems', 'pickupSlot'])
            ->latest()
            ->take(6)
            ->get();

        $bestSellers = Product::where('farmer_id', $farmer->id)
            ->withCount(['orderItems as total_sold' => function ($q) {
                $q->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', Order::STATUS_COMPLETED)
                    ->select(DB::raw('COALESCE(SUM(order_items.quantity), 0)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        $salesChartLabels = [];
        $salesChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $salesChartLabels[] = now()->subDays($i)->format('D, M d');
            $salesChartData[] = (float) Order::where('farmer_id', $farmer->id)
                ->where('status', Order::STATUS_COMPLETED)
                ->whereDate('completed_at', $date)
                ->sum('total');
        }

        $chartData = [
            'labels' => $salesChartLabels,
            'values' => $salesChartData,
        ];

        $lowStockProducts = Product::where('farmer_id', $farmer->id)
            ->where(fn($q) => $q->where('quantity', '<=', 5)->orWhere('availability_status', 'sold_out'))
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact(
            'farmer',
            'stats',
            'chartData',
            'totalOrders',
            'pendingOrders',
            'readyOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            'bestSellers',
            'salesChartLabels',
            'salesChartData',
            'lowStockProducts'
        ));
    }
}
