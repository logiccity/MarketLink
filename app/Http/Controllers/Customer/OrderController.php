<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected CartService $cartService
    ) {
    }

    public function index(Request $request)
    {
        $customer = auth()->user()->customer;
        $query = Order::where('customer_id', $customer->id)
            ->with(['farmer', 'market', 'items', 'pickupSlot']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($farmerId = $request->query('farmer')) {
            $query->where('farmer_id', $farmerId);
        }

        if ($from = $request->query('from')) {
            $query->whereDate('pickup_date', '>=', $from);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $farmers = \App\Models\Farmer::where('approval_status', 'approved')->orderBy('stall_name')->get();

        return view('customer.orders.index', compact('orders', 'farmers'));
    }

    public function show(Order $order)
    {
        $customer = auth()->user()->customer;
        if ($order->customer_id !== $customer->id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['farmer.user', 'market', 'items.product', 'pickupSlot', 'reviews']);

        $canModify = $order->canCustomerModifyOrCancel();

        return view('customer.orders.show', compact('order', 'canModify'));
    }

    public function cancel(Request $request, Order $order)
    {
        $customer = auth()->user()->customer;
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->orderService->cancelOrder($order, 'customer', $request->input('reason', 'Customer requested cancellation.'));
            return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order has been successfully cancelled.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function modify(Request $request, Order $order)
    {
        $customer = auth()->user()->customer;
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        if (!$order->canCustomerModifyOrCancel()) {
            return back()->with('error', 'This order can no longer be modified because the pickup cutoff time has passed or the seller has already accepted it.');
        }

        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update([
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'Order notes updated successfully.');
    }

    /**
     * Reorder items from previous order
     */
    public function reorder(Order $order)
    {
        $customer = auth()->user()->customer;
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $order->load('items.product');

        $addedCount = 0;
        $unavailableCount = 0;
        $messages = [];

        foreach ($order->items as $item) {
            $product = $item->product;

            if (!$product || $product->availability_status === 'sold_out' || $product->quantity <= 0) {
                $unavailableCount++;
                $messages[] = "'{$item->product_name_snapshot}' is currently sold out.";
                continue;
            }
            $qtyToAdd = min($item->quantity, $product->quantity);
            $res = $this->cartService->add($product->id, $qtyToAdd);

            if ($res['success']) {
                $addedCount++;
            } else {
                $unavailableCount++;
                $messages[] = "'{$product->name}': " . $res['message'];
            }
        }

        if ($addedCount > 0) {
            $msg = "{$addedCount} product(s) added to your basket!";
            if ($unavailableCount > 0) {
                $msg .= " However, {$unavailableCount} item(s) could not be re-ordered: " . implode(' ', $messages);
            }
            return redirect()->route('cart.index')->with('success', $msg);
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('warning', 'None of the products in this order are currently available: ' . implode(' ', $messages));
    }
}
