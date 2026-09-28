<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderStatusChangedNotification;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(
        Customer $customer,
        int $farmerId,
        int $marketId,
        int $pickupSlotId,
        string $pickupDate,
        array $items,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use ($customer, $farmerId, $marketId, $pickupSlotId, $pickupDate, $items, $notes) {
            $farmer = Farmer::with('user')->findOrFail($farmerId);
            $market = Market::findOrFail($marketId);
            $slot = PickupSlot::findOrFail($pickupSlotId);

            if ($slot->farmer_id !== $farmerId || $slot->market_id !== $marketId) {
                throw new Exception('The selected pickup slot is not valid for this farmer and market.');
            }

            if ($slot->isCutoffPassed()) {
                throw new Exception('The order cutoff time for this pickup window has already passed.');
            }

            if ($slot->isFull()) {
                throw new Exception('The selected pickup slot is completely full. Please choose another time.');
            }

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($items as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->farmer_id !== $farmerId) {
                    throw new Exception("Product '{$product->name}' does not belong to the selected farmer.");
                }

                $qty = (int) $item['quantity'];
                if ($qty <= 0) {
                    throw new Exception("Invalid quantity for product '{$product->name}'.");
                }

                if ($product->quantity < $qty || $product->availability_status === 'sold_out') {
                    throw new Exception("Insufficient stock for '{$product->name}'. Only {$product->quantity} available.");
                }

                $itemSubtotal = round($product->price * $qty, 2);
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'unit' => $product->unit,
                    'subtotal' => $itemSubtotal,
                ];
            }

            if (empty($orderItemsData)) {
                throw new Exception('Cannot place an order with zero valid items.');
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'farmer_id' => $farmer->id,
                'market_id' => $market->id,
                'pickup_slot_id' => $slot->id,
                'order_number' => Order::generateOrderNumber(),
                'pickup_date' => $pickupDate,
                'status' => Order::STATUS_PLACED,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'payment_method' => 'Pay at Market Pickup',
                'notes' => $notes,
                'placed_at' => now(),
            ]);

            foreach ($orderItemsData as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product']->id,
                    'product_name_snapshot' => $itemData['product']->name,
                    'unit_price_snapshot' => $itemData['price'],
                    'unit_snapshot' => $itemData['unit'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['subtotal'],
                ]);

                $product = $itemData['product'];
                $product->decrement('quantity', $itemData['quantity']);

                $remaining = $product->fresh()->quantity;
                if ($remaining <= 0) {
                    $product->update(['availability_status' => 'sold_out']);
                } elseif ($remaining <= 5) {
                    $product->update(['availability_status' => 'low_stock']);
                }
            }

            $slot->increment('booked_count');
            $customer->user->notify(new OrderPlacedNotification($order));

            return $order;
        });
    }

    public function cancelOrder(Order $order, string $cancelledBy, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($order, $cancelledBy, $reason) {
            if ($cancelledBy === 'customer' && !$order->canCustomerModifyOrCancel()) {
                throw new Exception('This order cannot be cancelled as it has already been processed or the cutoff time has passed.');
            }

            if (in_array($order->status, [Order::STATUS_CANCELLED, Order::STATUS_COMPLETED, Order::STATUS_DECLINED], true)) {
                throw new Exception('Order is already finalised.');
            }

            $previousStatus = $order->status;

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                    if ($item->product->availability_status === 'sold_out' && $item->product->quantity > 0) {
                        $item->product->update(['availability_status' => 'available']);
                    }
                }
            }

            if ($order->pickupSlot) {
                $order->pickupSlot->decrement('booked_count');
            }

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'cancelled_by' => $cancelledBy,
                'cancellation_reason' => $reason,
            ]);

            $order->customer->user->notify(new OrderStatusChangedNotification($order, $previousStatus, $reason));

            return true;
        });
    }

    public function updateStatus(Order $order, string $newStatus, ?string $reason = null): void
    {
        DB::transaction(function () use ($order, $newStatus, $reason) {
            $previousStatus = $order->status;

            if ($newStatus === Order::STATUS_DECLINED || $newStatus === Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                        if ($item->product->availability_status === 'sold_out' && $item->product->quantity > 0) {
                            $item->product->update(['availability_status' => 'available']);
                        }
                    }
                }

                if ($order->pickupSlot) {
                    $order->pickupSlot->decrement('booked_count');
                }
            }

            $updates = ['status' => $newStatus];
            if ($newStatus === Order::STATUS_COMPLETED) {
                $updates['completed_at'] = now();
            }
            if ($newStatus === Order::STATUS_DECLINED) {
                $updates['decline_reason'] = $reason;
            }

            $order->update($updates);
            $order->customer->user->notify(new OrderStatusChangedNotification($order, $previousStatus, $reason));
        });
    }
}
