<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Market;
use App\Models\PickupSlot;
use App\Services\CartService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart();
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Your basket is empty. Please add items before proceeding to checkout.');
        }

        $user = auth()->user();
        if (!$user->isCustomer() && !$user->isAdmin()) {
            return redirect()->route('cart.index')->with('error', 'Only customer accounts can place pre-orders.');
        }

        $customer = $user->customer;
        if (!$customer) {
            return redirect()->route('cart.index')->with('error', 'Customer profile not found.');
        }

        $grouped = $this->cartService->getGroupedByFarmer();
        $subtotal = $this->cartService->subtotal();

        $farmerCheckouts = [];
        foreach ($grouped as $farmerId => $farmerData) {
            $farmer = Farmer::with('markets')->findOrFail($farmerId);
            $markets = $farmer->markets;

            $pickupSlots = PickupSlot::where('farmer_id', $farmerId)
                ->where('is_active', true)
                ->where('pickup_date', '>=', now()->toDateString())
                ->orderBy('pickup_date')
                ->orderBy('start_time')
                ->get()
                ->filter(fn ($slot) => $slot->isAvailable());

            $farmerCheckouts[$farmerId] = [
                'farmer' => $farmer,
                'items' => $farmerData['items'],
                'subtotal' => $farmerData['subtotal'],
                'markets' => $markets,
                'pickupSlots' => $pickupSlots,
            ];
        }

        $familyMembers = $customer->preferences['family_members'] ?? [];

        return view('checkout.index', compact('farmerCheckouts', 'subtotal', 'familyMembers'));
    }

    /**
     * Process pre-order placement
     */
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'farmer_id' => ['required', 'integer', 'exists:farmers,id'],
            'market_id' => ['required', 'integer', 'exists:markets,id'],
            'pickup_slot_id' => ['required', 'integer', 'exists:pickup_slots,id'],
            'pickup_person' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = auth()->user();
        $customer = $user->customer;
        if (!$customer) {
            return back()->with('error', 'Customer profile not found.');
        }

        $cart = $this->cartService->getCart();
        $farmerId = (int) $validated['farmer_id'];

        $farmerItems = [];
        foreach ($cart as $productId => $item) {
            if ((int) $item['farmer_id'] === $farmerId) {
                $farmerItems[] = $item;
            }
        }

        if (empty($farmerItems)) {
            return back()->with('error', 'No items in basket for this farmer.');
        }

        $slot = PickupSlot::findOrFail($validated['pickup_slot_id']);

        $finalNotes = $validated['notes'] ?? '';
        if (!empty($validated['pickup_person']) && $validated['pickup_person'] !== 'self') {
            $prefix = "[Pickup By: {$validated['pickup_person']}]";
            $finalNotes = $finalNotes ? "{$prefix} {$finalNotes}" : $prefix;
        }

        try {
            $order = $this->orderService->placeOrder(
                customer: $customer,
                farmerId: $farmerId,
                marketId: (int) $validated['market_id'],
                pickupSlotId: $slot->id,
                pickupDate: $slot->pickup_date->toDateString(),
                items: $farmerItems,
                notes: $finalNotes ?: null
            );

            foreach ($farmerItems as $item) {
                $this->cartService->remove($item['product_id']);
            }

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Pre-order ' . $order->order_number . ' placed successfully! Payment is due in person upon pickup at ' . $order->market->name . '.');
        } catch (Exception $e) {
            return back()->with('error', 'Could not complete order: ' . $e->getMessage())->withInput();
        }
    }
}
