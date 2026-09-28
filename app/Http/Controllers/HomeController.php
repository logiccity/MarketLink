<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'farmers'   => Farmer::where('approval_status', 'approved')->count(),
            'products'  => Product::where('availability_status', '!=', 'sold_out')->count(),
            'markets'   => Market::where('status', 'active')->count(),
            'orders'    => Order::count(),
            'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'customers' => \App\Models\User::where('role', 'customer')->count(),
        ];

        $featuredMarkets = Market::where('status', 'active')
            ->withCount([
                'farmers' => function ($q) {
                    $q->where('approval_status', 'approved');
                },
                'products' => function ($q) {
                    $q->where('availability_status', '!=', 'sold_out');
                }
            ])
            ->take(6)
            ->get();

        $featuredFarmers = Farmer::where('approval_status', 'approved')
            ->with(['markets', 'user'])
            ->withCount(['products' => function ($q) {
                $q->where('availability_status', '!=', 'sold_out');
            }])
            ->take(4)
            ->get();

        $featuredProducts = Product::where('availability_status', '!=', 'sold_out')
            ->with(['farmer', 'category', 'images', 'reviews'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('status', 'active')
            ->withCount(['products' => function ($q) {
                $q->where('availability_status', '!=', 'sold_out');
            }])
            ->get();

        $recentReviews = Review::where('status', 'approved')
            ->with(['customer.user', 'product', 'farmer'])
            ->latest()
            ->take(6)
            ->get();

        $announcements = Announcement::active()->latest()->take(3)->get();

        return view('home', compact(
            'stats',
            'featuredMarkets',
            'featuredFarmers',
            'featuredProducts',
            'categories',
            'recentReviews',
            'announcements'
        ));
    }
}
