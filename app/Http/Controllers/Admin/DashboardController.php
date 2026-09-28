<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Customer;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->query('range', '30d');
        $now = Carbon::now();
        $startDate = match ($range) {
            'today'  => $now->copy()->startOfDay(),
            '7d'     => $now->copy()->subDays(6)->startOfDay(),
            '30d'    => $now->copy()->subDays(29)->startOfDay(),
            'month'  => $now->copy()->startOfMonth(),
            'year'   => $now->copy()->startOfYear(),
            'all'    => Carbon::createFromTimestamp(0),
            'custom' => $request->filled('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : $now->copy()->subDays(29)->startOfDay(),
            default  => $now->copy()->subDays(29)->startOfDay(),
        };

        $endDate = ($range === 'custom' && $request->filled('end_date'))
            ? Carbon::parse($request->query('end_date'))->endOfDay()
            : $now->copy()->endOfDay();

        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalFarmers = Farmer::count();
        $approvedFarmers = Farmer::where('approval_status', 'approved')->count();
        $pendingFarmersCount = Farmer::where('approval_status', 'pending')->count();
        $newFarmers = Farmer::whereBetween('created_at', [$startDate, $endDate])->count();

        $totalMarkets = Market::count();
        $activeMarkets = Market::where('status', 'active')->count();

        $totalProducts = Product::count();
        $activeProducts = Product::where('availability_status', '!=', 'sold_out')->count();

        $totalOrdersAllTime = Order::count();
        $filteredOrdersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        $totalOrders = (clone $filteredOrdersQuery)->count();
        $completedOrders = (clone $filteredOrdersQuery)->where('status', Order::STATUS_COMPLETED)->count();
        $pendingOrders = (clone $filteredOrdersQuery)->whereIn('status', [
            Order::STATUS_PLACED,
            Order::STATUS_ACCEPTED,
            Order::STATUS_READY_FOR_PICKUP,
        ])->count();
        $cancelledOrders = (clone $filteredOrdersQuery)->whereIn('status', [
            Order::STATUS_CANCELLED,
            Order::STATUS_DECLINED,
        ])->count();

        $totalRevenue = (float) (clone $filteredOrdersQuery)->where('status', Order::STATUS_COMPLETED)->sum('total');
        $totalRevenueAllTime = (float) Order::where('status', Order::STATUS_COMPLETED)->sum('total');

        $totalReviews = Review::count();
        $avgRating = round((float) (Review::where('status', 'approved')->avg('rating') ?: 5.0), 1);

        $statusBreakdown = [
            'Placed'    => Order::where('status', Order::STATUS_PLACED)->count(),
            'Accepted'  => Order::where('status', Order::STATUS_ACCEPTED)->count(),
            'Ready'     => Order::where('status', Order::STATUS_READY_FOR_PICKUP)->count(),
            'Completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'Cancelled' => Order::whereIn('status', [Order::STATUS_CANCELLED, Order::STATUS_DECLINED])->count(),
        ];

        $chartData = $this->buildChartData($range, $statusBreakdown);

        $recentOrders = Order::with(['customer.user', 'farmer', 'market'])
            ->latest('id')
            ->take(8)
            ->get();

        $pendingFarmers = Farmer::where('approval_status', 'pending')
            ->with(['user', 'markets'])
            ->latest('id')
            ->take(5)
            ->get();

        $recentFarmers = Farmer::where('approval_status', 'approved')
            ->with(['user', 'markets'])
            ->withCount('products')
            ->latest('id')
            ->take(5)
            ->get();

        $recentCustomers = Customer::with('user')
            ->withCount('orders')
            ->latest('id')
            ->take(5)
            ->get();

        $topProducts = Product::with(['category', 'farmer'])
            ->withCount(['orderItems as orders_count' => function ($q) {
                $q->select(DB::raw('COALESCE(SUM(order_items.quantity), 0)'));
            }])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        $recentReviews = Review::with(['customer.user', 'farmer', 'product'])
            ->latest('id')
            ->take(4)
            ->get();

        $recentContacts = ContactMessage::latest('id')->take(4)->get();

        $recentMarkets = Market::withCount(['farmers', 'orders'])
            ->latest('id')
            ->take(4)
            ->get();

        $recentActivity = $this->buildRecentActivity();

        $stats = [
            'customers'          => $totalCustomers,
            'new_customers'      => $newCustomers,
            'farmers'            => $approvedFarmers,
            'total_farmers'      => $totalFarmers,
            'pending_farmers'    => $pendingFarmersCount,
            'new_farmers'        => $newFarmers,
            'markets'            => $totalMarkets,
            'active_markets'     => $activeMarkets,
            'products'           => $totalProducts,
            'active_products'    => $activeProducts,
            'orders'             => $totalOrders,
            'total_orders'       => $totalOrdersAllTime,
            'completed_orders'   => $completedOrders,
            'pending_orders'     => $pendingOrders,
            'cancelled_orders'   => $cancelledOrders,
            'revenue'            => $totalRevenue,
            'revenue_all_time'   => $totalRevenueAllTime,
            'reviews_count'      => $totalReviews,
            'avg_rating'         => $avgRating,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'range',
            'startDate',
            'endDate',
            'chartData',
            'recentOrders',
            'pendingFarmers',
            'recentFarmers',
            'recentCustomers',
            'recentMarkets',
            'topProducts',
            'recentReviews',
            'recentContacts',
            'recentActivity'
        ));
    }

    private function buildChartData(string $range, array $statusBreakdown): array
    {
        $chartLabels = [];
        $chartOrderCounts = [];
        $chartRevenues = [];

        if (in_array($range, ['today', '7d'])) {
            for ($i = 6; $i >= 0; $i--) {
                $day = Carbon::now()->subDays($i);
                $dateStr = $day->format('Y-m-d');
                $chartLabels[] = $day->format('D, M j');
                $chartOrderCounts[] = Order::whereDate('created_at', $dateStr)->count();
                $chartRevenues[] = (float) Order::where('status', Order::STATUS_COMPLETED)
                    ->whereDate('created_at', $dateStr)
                    ->sum('total');
            }
        } elseif ($range === 'year' || $range === 'all') {
            for ($i = 11; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $chartLabels[] = $month->format('M Y');
                $chartOrderCounts[] = Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
                $chartRevenues[] = (float) Order::where('status', Order::STATUS_COMPLETED)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total');
            }
        } else {
            $intervals = 10;
            $intervalLength = max(1, (int) ceil(30 / $intervals));
            for ($i = $intervals - 1; $i >= 0; $i--) {
                $startInterval = Carbon::now()->subDays(($i + 1) * $intervalLength);
                $endInterval = Carbon::now()->subDays($i * $intervalLength);
                $chartLabels[] = $endInterval->format('M j');
                $chartOrderCounts[] = Order::whereBetween('created_at', [$startInterval, $endInterval])->count();
                $chartRevenues[] = (float) Order::where('status', Order::STATUS_COMPLETED)
                    ->whereBetween('created_at', [$startInterval, $endInterval])
                    ->sum('total');
            }
        }

        return [
            'labels'          => $chartLabels,
            'orders'          => $chartOrderCounts,
            'revenue'         => $chartRevenues,
            'statusBreakdown' => $statusBreakdown,
        ];
    }

    private function buildRecentActivity(): Collection
    {
        $activities = collect();

        foreach (Order::with(['customer.user', 'farmer'])->latest('id')->take(6)->get() as $ord) {
            $activities->push([
                'type'      => 'order',
                'title'     => 'Reservation Placed #' . $ord->order_number,
                'desc'      => ($ord->customer->user->name ?? 'A customer') . ' placed a harvest reservation with ' . ($ord->farmer->stall_name ?? 'grower') . ' (PKR ' . number_format($ord->total, 2) . ')',
                'timestamp' => $ord->created_at,
                'icon'      => 'receipt',
                'color'     => '#16845B',
                'url'       => route('admin.orders.show', $ord),
            ]);
        }

        foreach (Farmer::with('user')->latest('id')->take(4)->get() as $farm) {
            $activities->push([
                'type'      => 'farmer',
                'title'     => 'Grower Stall: ' . $farm->stall_name,
                'desc'      => 'Producer application (' . ($farm->contact_person ?? $farm->user->name ?? 'Contact') . ') status is currently ' . $farm->approval_status . '.',
                'timestamp' => $farm->created_at,
                'icon'      => 'person-badge',
                'color'     => '#D4B477',
                'url'       => route('admin.farmers.show', $farm),
            ]);
        }

        foreach (Customer::with('user')->latest('id')->take(4)->get() as $cust) {
            if ($cust->user) {
                $activities->push([
                    'type'      => 'customer',
                    'title'     => 'Patron Enrolled: ' . $cust->user->name,
                    'desc'      => 'New customer profile registered under ' . $cust->user->email . '.',
                    'timestamp' => $cust->created_at,
                    'icon'      => 'people',
                    'color'     => '#2563EB',
                    'url'       => route('admin.customers.show', $cust),
                ]);
            }
        }

        foreach (Review::with(['customer.user', 'farmer'])->latest('id')->take(3)->get() as $rev) {
            $activities->push([
                'type'      => 'review',
                'title'     => 'Patron Review (' . $rev->rating . ' ★)',
                'desc'      => ($rev->customer->user->name ?? 'Customer') . ' reviewed stall ' . ($rev->farmer->stall_name ?? 'grower') . '.',
                'timestamp' => $rev->created_at,
                'icon'      => 'star-half',
                'color'     => '#D97706',
                'url'       => route('admin.reviews.index'),
            ]);
        }

        return $activities->sortByDesc('timestamp')->take(8)->values();
    }

    /**
     * Real Global Admin Search across Customers, Farmers, Markets, Products, Orders.
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        if (empty($q)) {
            return view('admin.search', [
                'q'         => '',
                'orders'    => collect(),
                'farmers'   => collect(),
                'customers' => collect(),
                'markets'   => collect(),
                'products'  => collect(),
                'total'     => 0,
            ]);
        }

        $orders = Order::where('order_number', 'like', "%{$q}%")
            ->orWhere('status', 'like', "%{$q}%")
            ->with(['customer.user', 'farmer', 'market'])
            ->latest('id')
            ->take(15)
            ->get();

        $farmers = Farmer::where('stall_name', 'like', "%{$q}%")
            ->orWhere('contact_person', 'like', "%{$q}%")
            ->orWhereHas('user', fn($sq) => $sq->where('email', 'like', "%{$q}%"))
            ->with(['user', 'markets'])
            ->take(15)
            ->get();

        $customers = Customer::whereHas('user', fn($sq) =>
            $sq->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")
        )->with('user')->take(15)->get();

        $markets = Market::where('name', 'like', "%{$q}%")
            ->orWhere('city', 'like', "%{$q}%")
            ->orWhere('address', 'like', "%{$q}%")
            ->take(15)
            ->get();

        $products = Product::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->with(['category', 'farmer'])
            ->take(15)
            ->get();

        $total = $orders->count() + $farmers->count() + $customers->count() + $markets->count() + $products->count();

        return view('admin.search', compact('q', 'orders', 'farmers', 'customers', 'markets', 'products', 'total'));
    }

    /**
     * Admin Notifications Center.
     */
    public function notifications(Request $request)
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(15);
        $unreadCount = $user->unreadNotifications()->count();

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markNotificationRead(string $id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
