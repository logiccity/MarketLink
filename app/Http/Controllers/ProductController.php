<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['farmer.markets', 'category', 'images', 'reviews'])
            ->whereHas('farmer', fn($q) => $q->where('approval_status', 'approved'));

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('farmer', fn($f) => $f->where('stall_name', 'like', "%{$search}%"))
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('farmer.markets', fn($m) => $m->where('name', 'like', "%{$search}%"));
            });
        }

        $activeCategoryName = null;
        if ($categoryParam = $request->query('category')) {
            $activeCategory = Category::where(function ($q) use ($categoryParam) {
                if (is_numeric($categoryParam)) {
                    $q->where('id', $categoryParam);
                } else {
                    $q->where('slug', $categoryParam);
                }
            })->first();

            if ($activeCategory) {
                $activeCategoryName = $activeCategory->name;
                $query->where('category_id', $activeCategory->id);
            } else {
                $query->whereHas('category', function ($q) use ($categoryParam) {
                    $q->where('slug', $categoryParam)->orWhere('id', $categoryParam);
                });
            }
        }

        if ($marketId = ($request->query('market_id') ?: $request->query('market'))) {
            $query->where(function ($q) use ($marketId) {
                $q->where('market_id', $marketId)
                    ->orWhereHas('farmer.markets', fn($m) => $m->where('markets.id', $marketId));
            });
        }

        if ($farmerId = $request->query('farmer_id')) {
            $query->where('farmer_id', $farmerId);
        }

        if ($minPrice = $request->query('min_price')) {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice = $request->query('max_price')) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($day = $request->query('day')) {
            $query->where(function ($q) use ($day) {
                $q->whereHas('farmer', fn($f) => $f->whereJsonContains('operating_days', $day))
                    ->orWhereHas('farmer.markets', fn($m) => $m->whereJsonContains('operating_days', $day));
            });
        }

        if ($request->boolean('organic')) {
            $query->where(function ($q) {
                $q->where('is_organic', true)
                    ->orWhereHas('farmer', fn($f) => $f->where('is_organic', true));
            });
        }

        if ($request->boolean('in_stock') || $request->query('availability') === 'in_stock') {
            $query->where('quantity', '>', 0)->where('availability_status', '!=', 'sold_out');
        } elseif ($request->query('availability') === 'sold_out') {
            $query->where(function ($q) {
                $q->where('quantity', '<=', 0)->orWhere('availability_status', 'sold_out');
            });
        }

        match ($request->query('sort', 'latest')) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $markets = Market::where('status', 'active')->orderBy('name')->get();
        $farmers = Farmer::where('approval_status', 'approved')->orderBy('stall_name')->get();

        return view('products.index', compact('products', 'categories', 'markets', 'farmers', 'activeCategoryName'));
    }

    public function show(Product $product)
    {
        $product->load([
            'farmer.markets',
            'farmer.pickupSlots' => function ($q) {
                $q->where('is_active', true)
                    ->where('pickup_date', '>=', now()->toDateString())
                    ->orderBy('pickup_date');
            },
            'category',
            'images',
            'reviews' => function ($q) {
                $q->where('status', 'approved')->with('customer.user')->latest();
            }
        ]);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('availability_status', '!=', 'sold_out')
            ->with(['farmer', 'images'])
            ->take(4)
            ->get();

        $customer = auth()->user()?->customer;
        $isFavorited = $customer ? $customer->hasFavoritedProduct($product->id) : false;
        $pickupSlots = $product->farmer?->pickupSlots ?? collect();

        $reviews = $product->reviews()
            ->where('status', 'approved')
            ->with('customer.user')
            ->latest()
            ->paginate(5);

        $canReview = false;
        if ($customer) {
            $hasCompletedOrder = Order::where('customer_id', $customer->id)
                ->where('farmer_id', $product->farmer_id)
                ->where('status', Order::STATUS_COMPLETED)
                ->exists();

            $alreadyReviewed = Review::where('customer_id', $customer->id)
                ->where('product_id', $product->id)
                ->exists();

            $canReview = $hasCompletedOrder && !$alreadyReviewed;
        }

        return view('products.show', compact('product', 'relatedProducts', 'isFavorited', 'pickupSlots', 'reviews', 'canReview'));
    }
}
