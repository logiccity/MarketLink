<?php

namespace App\Services;

use App\Models\Farmer;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'egreen_cart';

    public function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public function add(int $productId, int $quantity = 1): array
    {
        $cart = $this->getCart();
        $product = Product::with(['farmer', 'category'])->find($productId);

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        if ($product->quantity <= 0 || $product->availability_status === 'sold_out') {
            return ['success' => false, 'message' => 'Sorry, this product is currently sold out.'];
        }

        $currentQty = $cart[$productId]['quantity'] ?? 0;
        $requestedTotal = $currentQty + $quantity;

        if ($requestedTotal > $product->quantity) {
            return [
                'success' => false,
                'message' => "Cannot add requested quantity. Only {$product->quantity} {$product->unit} available in stock."
            ];
        }

        $cart[$productId] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'unit' => $product->unit,
            'image' => $product->primary_image_url,
            'farmer_id' => $product->farmer_id,
            'farmer_name' => $product->farmer->stall_name ?? 'Farmer',
            'market_id' => $product->market_id,
            'quantity' => $requestedTotal,
            'stock_available' => $product->quantity,
        ];

        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => $product->name . ' added to your basket.',
            'cart_count' => $this->count(),
            'cart_subtotal' => $this->subtotal(),
        ];
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = $this->getCart();

        if (!isset($cart[$productId])) {
            return ['success' => false, 'message' => 'Product not in basket.'];
        }

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        $product = Product::find($productId);
        if (!$product || $quantity > $product->quantity) {
            $available = $product ? $product->quantity : 0;
            return [
                'success' => false,
                'message' => "Only {$available} items available in stock."
            ];
        }

        $cart[$productId]['quantity'] = $quantity;
        $cart[$productId]['price'] = (float) $product->price;
        Session::put(self::SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => 'Quantity updated.',
            'item_subtotal' => $quantity * (float) $cart[$productId]['price'],
            'cart_count' => $this->count(),
            'cart_subtotal' => $this->subtotal(),
        ];
    }

    public function remove(int $productId): array
    {
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put(self::SESSION_KEY, $cart);
        }

        return [
            'success' => true,
            'message' => 'Item removed from basket.',
            'cart_count' => $this->count(),
            'cart_subtotal' => $this->subtotal(),
        ];
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        $count = 0;
        foreach ($this->getCart() as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public function subtotal(): float
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return round($total, 2);
    }

    public function getGroupedByFarmer(): array
    {
        $grouped = [];

        foreach ($this->getCart() as $productId => $item) {
            $farmerId = $item['farmer_id'];

            if (!isset($grouped[$farmerId])) {
                $farmer = Farmer::with('markets')->find($farmerId);
                $grouped[$farmerId] = [
                    'farmer' => $farmer,
                    'farmer_name' => $item['farmer_name'],
                    'items' => [],
                    'subtotal' => 0,
                ];
            }

            $itemSubtotal = $item['price'] * $item['quantity'];
            $grouped[$farmerId]['items'][$productId] = $item;
            $grouped[$farmerId]['subtotal'] += $itemSubtotal;
        }

        return $grouped;
    }

    public function validateAvailability(): array
    {
        $errors = [];
        $cart = $this->getCart();

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product || $product->availability_status === 'sold_out') {
                $errors[] = "Product '{$item['name']}' is no longer available.";
            } elseif ($product->quantity < $item['quantity']) {
                $errors[] = "Only {$product->quantity} of '{$product->name}' available (you had {$item['quantity']} in cart).";
            }
        }

        return $errors;
    }
}
