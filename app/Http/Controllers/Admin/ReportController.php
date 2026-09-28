<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->query('end_date', now()->toDateString());
        $selectedMarketId = $request->query('market_id');

        $ordersBase = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($selectedMarketId) {
            $ordersBase->where('market_id', $selectedMarketId);
        }

        $totalOrdersCount = (clone $ordersBase)->count();
        $completedOrdersCount = (clone $ordersBase)->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = (clone $ordersBase)->where('status', Order::STATUS_COMPLETED)->sum('total');

        $revenueByMarket = Market::withCount(['orders' => function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }])
        ->withSum(['orders as total_revenue' => function ($q) use ($startDate, $endDate) {
            $q->where('status', Order::STATUS_COMPLETED)
              ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }], 'total')
        ->orderBy('total_revenue', 'desc')
        ->get();

        $topFarmers = Farmer::withCount(['orders' => function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }])
        ->withSum(['orders as total_sales' => function ($q) use ($startDate, $endDate) {
            $q->where('status', Order::STATUS_COMPLETED)
              ->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }], 'total')
        ->orderBy('orders_count', 'desc')
        ->take(10)
        ->get();

        $topProducts = Product::withCount(['orderItems as units_ordered' => function ($q) use ($startDate, $endDate) {
            $q->join('orders', 'order_items.order_id', '=', 'orders.id')
              ->whereDate('orders.created_at', '>=', $startDate)->whereDate('orders.created_at', '<=', $endDate)
              ->select(DB::raw('COALESCE(SUM(order_items.quantity), 0)'));
        }])
        ->orderBy('units_ordered', 'desc')
        ->take(10)
        ->get();

        $markets = Market::where('status', 'active')->orderBy('name')->get();

        $isPrint = $request->boolean('print');
        if ($isPrint) {
            return view('admin.reports.print', compact(
                'startDate',
                'endDate',
                'selectedMarketId',
                'totalOrdersCount',
                'completedOrdersCount',
                'totalRevenue',
                'revenueByMarket',
                'topFarmers',
                'topProducts',
                'markets'
            ));
        }

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'selectedMarketId',
            'totalOrdersCount',
            'completedOrdersCount',
            'totalRevenue',
            'revenueByMarket',
            'topFarmers',
            'topProducts',
            'markets'
        ));
    }
}
