<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService)
    {
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $grouped = $this->cartService->getGroupedByFarmer();
        $subtotal = $this->cartService->subtotal();
        $validationErrors = $this->cartService->validateAvailability();

        return view('cart.index', compact('cart', 'grouped', 'subtotal', 'validationErrors'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $qty = (int) $request->input('quantity', 1);
        $result = $this->cartService->add((int) $request->product_id, $qty);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $result = $this->cartService->update((int) $request->product_id, (int) $request->quantity);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return redirect()->route('cart.index')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function remove(Request $request, int $productId)
    {
        $result = $this->cartService->remove($productId);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from your basket.');
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('cart.index')->with('success', 'Your basket has been emptied.');
    }
}
