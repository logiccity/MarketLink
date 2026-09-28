<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        $farmer = auth()->user()->farmer;
        $query = Order::where('farmer_id', $farmer->id)
            ->with(['customer.user', 'market', 'items', 'orderItems', 'pickupSlot']);

        if ($status = $request->query('status')) {
            if ($status === 'CANCELLED') {
                $query->whereIn('status', [Order::STATUS_CANCELLED, Order::STATUS_DECLINED]);
            } elseif ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        if ($date = $request->query('date')) {
            $query->whereDate('pickup_date', $date);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $pendingCount = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_PLACED)->count();
        $acceptedCount = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_ACCEPTED)->count();
        $readyCount = Order::where('farmer_id', $farmer->id)->where('status', Order::STATUS_READY_FOR_PICKUP)->count();

        return view('farmer.orders.index', compact('orders', 'farmer', 'pendingCount', 'acceptedCount', 'readyCount'));
    }

    public function show(Order $order)
    {
        $farmer = auth()->user()->farmer;
        if ($order->farmer_id !== $farmer->id) {
            abort(403);
        }

        $order->load(['customer.user', 'market', 'items.product', 'pickupSlot', 'reviews.responses']);

        return view('farmer.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $farmer = auth()->user()->farmer;
        if ($order->farmer_id !== $farmer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:ACCEPTED,DECLINED,READY_FOR_PICKUP,COMPLETED'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $validated['status'];

        $validTransitions = [
            Order::STATUS_PLACED => [Order::STATUS_ACCEPTED, Order::STATUS_DECLINED],
            Order::STATUS_ACCEPTED => [Order::STATUS_READY_FOR_PICKUP, Order::STATUS_DECLINED],
            Order::STATUS_READY_FOR_PICKUP => [Order::STATUS_COMPLETED],
        ];

        if (!isset($validTransitions[$order->status]) || !in_array($newStatus, $validTransitions[$order->status], true)) {
            return back()->with('error', "Cannot transition order from {$order->status} to {$newStatus}.");
        }

        try {
            $this->orderService->updateStatus($order, $newStatus, $validated['reason'] ?? null);
            return back()->with('success', "Order #{$order->order_number} marked as {$order->fresh()->status_label}.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirm(Order $order)
    {
        $farmer = auth()->user()->farmer;
        if ($order->farmer_id !== $farmer->id) {
            abort(403);
        }

        try {
            $this->orderService->updateStatus($order, Order::STATUS_ACCEPTED);
            return back()->with('success', "Order #{$order->order_number} has been confirmed.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
