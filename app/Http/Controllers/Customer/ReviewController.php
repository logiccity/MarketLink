<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show all reviews written by this customer
     */
    public function index()
    {
        $customer = auth()->user()->customer;

        $reviews = Review::where('customer_id', $customer->id)
            ->with(['product', 'farmer', 'order'])
            ->latest()
            ->paginate(10);

        $totalReviews  = Review::where('customer_id', $customer->id)->count();
        $avgRating     = Review::where('customer_id', $customer->id)->avg('rating') ?? 0;
        $farmersReviewed = Review::where('customer_id', $customer->id)
            ->distinct('farmer_id')->count('farmer_id');
        $reviewedOrderIds = Review::where('customer_id', $customer->id)
            ->whereNotNull('order_id')
            ->pluck('order_id')
            ->toArray();

        $pendingReviewOrders = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->whereNotIn('id', $reviewedOrderIds)
            ->with(['farmer', 'orderItems'])
            ->latest()
            ->take(6)
            ->get();

        $reviewableOrders = $pendingReviewOrders->count();

        return view('customer.reviews.index', compact(
            'reviews',
            'totalReviews',
            'avgRating',
            'farmersReviewed',
            'reviewableOrders',
            'pendingReviewOrders'
        ));
    }

    public function store(Request $request, Order $order)
    {
        $customer = auth()->user()->customer;
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        if ($order->status !== Order::STATUS_COMPLETED) {
            return back()->with('error', 'Reviews can only be submitted after your order is completed at market pickup.');
        }

        $validated = $request->validate([
            'rating'     => ['required', 'integer', 'between:1,5'],
            'comment'    => ['required', 'string', 'min:5', 'max:1000'],
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
        ]);
        $existing = Review::where('order_id', $order->id)
            ->where('customer_id', $customer->id)
            ->where('product_id', $validated['product_id'] ?? null)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        Review::create([
            'customer_id' => $customer->id,
            'farmer_id'   => $order->farmer_id,
            'product_id'  => $validated['product_id'] ?? null,
            'order_id'    => $order->id,
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'],
            'status'      => 'approved',
        ]);

        return back()->with('success', 'Thank you! Your review and rating have been posted.');
    }
}
