<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $farmer = auth()->user()->farmer;

        $startDate = $request->query('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());

        $ordersQuery = Order::where('farmer_id', $farmer->id)
            ->whereDate('pickup_date', '>=', $startDate)
            ->whereDate('pickup_date', '<=', $endDate);

        $totalOrders = (clone $ordersQuery)->count();
        $completedOrders = (clone $ordersQuery)->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = (clone $ordersQuery)->where('status', Order::STATUS_COMPLETED)->sum('total');
        $averageOrderValue = $completedOrders > 0 ? ($totalRevenue / $completedOrders) : 0;

        $bestSellers = Product::where('farmer_id', $farmer->id)
            ->withCount(['orderItems as total_units_sold' => function ($q) use ($startDate, $endDate) {
                $q->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', Order::STATUS_COMPLETED)
                    ->whereDate('orders.pickup_date', '>=', $startDate)
                    ->whereDate('orders.pickup_date', '<=', $endDate)
                    ->select(DB::raw('COALESCE(SUM(order_items.quantity), 0)'));
            }])
            ->orderBy('total_units_sold', 'desc')
            ->take(5)
            ->get();

        $dailySales = Order::where('farmer_id', $farmer->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereDate('pickup_date', '>=', $startDate)
            ->whereDate('pickup_date', '<=', $endDate)
            ->groupBy('pickup_date')
            ->select('pickup_date', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders_count'))
            ->orderBy('pickup_date')
            ->get();

        $chartDates = $dailySales->pluck('pickup_date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray();
        $chartRevenues = $dailySales->pluck('revenue')->toArray();

        return view('farmer.sales.index', compact(
            'farmer',
            'startDate',
            'endDate',
            'totalOrders',
            'completedOrders',
            'totalRevenue',
            'averageOrderValue',
            'bestSellers',
            'chartDates',
            'chartRevenues'
        ));
    }
}
