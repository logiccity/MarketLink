<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $favoriteProducts = $customer->favoriteProducts()->with(['farmer', 'images', 'category'])->paginate(12);
        $favoriteFarmers = $customer->favoriteFarmers()->with(['markets'])->get();

        $preferredMarketIds = $customer->preferences['preferred_market_ids'] ?? [];
        $favoriteMarkets = Market::whereIn('id', $preferredMarketIds)->withCount('farmers')->get();

        return view('customer.favorites.index', compact('favoriteProducts', 'favoriteFarmers', 'favoriteMarkets'));
    }

    public function toggle(Request $request)
    {
        $customer = auth()->user()->customer;
        if (!$customer) {
            $customer = auth()->user()->customer()->create(['preferences' => []]);
        }

        if ($productId = $request->input('product_id')) {
            $product = Product::findOrFail($productId);
            $exists = $customer->favoriteProducts()->where('product_id', $productId)->exists();

            if ($exists) {
                $customer->favoriteProducts()->detach($productId);
                return response()->json([
                    'success' => true,
                    'status' => 'removed',
                    'favorited' => false,
                    'message' => "'{$product->name}' removed from favorites.",
                ]);
            } else {
                $customer->favoriteProducts()->attach($productId);
                return response()->json([
                    'success' => true,
                    'status' => 'added',
                    'favorited' => true,
                    'message' => "'{$product->name}' saved to favorites!",
                ]);
            }
        }

        if ($farmerId = $request->input('farmer_id')) {
            $farmer = Farmer::findOrFail($farmerId);
            $exists = $customer->favoriteFarmers()->where('farmer_id', $farmerId)->exists();

            if ($exists) {
                $customer->favoriteFarmers()->detach($farmerId);
                return response()->json([
                    'success' => true,
                    'status' => 'removed',
                    'favorited' => false,
                    'message' => "'{$farmer->stall_name}' removed from favorite farmers.",
                ]);
            } else {
                $customer->favoriteFarmers()->attach($farmerId);
                return response()->json([
                    'success' => true,
                    'status' => 'added',
                    'favorited' => true,
                    'message' => "'{$farmer->stall_name}' added to favorite farmers!",
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid target ID.'], 422);
    }

    public function toggleProduct(Request $request, Product $product)
    {
        $customer = auth()->user()->customer;
        $exists = $customer->favoriteProducts()->where('product_id', $product->id)->exists();

        if ($exists) {
            $customer->favoriteProducts()->detach($product->id);
            $favorited = false;
            $message = "'{$product->name}' removed from your favorites.";
        } else {
            $customer->favoriteProducts()->attach($product->id);
            $favorited = true;
            $message = "'{$product->name}' added to your favorites!";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function toggleFarmer(Request $request, Farmer $farmer)
    {
        $customer = auth()->user()->customer;
        $exists = $customer->favoriteFarmers()->where('farmer_id', $farmer->id)->exists();

        if ($exists) {
            $customer->favoriteFarmers()->detach($farmer->id);
            $favorited = false;
            $message = "'{$farmer->stall_name}' removed from your favorite farmers.";
        } else {
            $customer->favoriteFarmers()->attach($farmer->id);
            $favorited = true;
            $message = "'{$farmer->stall_name}' added to your favorite farmers!";
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Save / Toggle preferred market
     */
    public function toggleMarket(Request $request, Market $market)
    {
        $customer = auth()->user()->customer;
        if (!$customer) {
            $customer = auth()->user()->customer()->create(['preferences' => []]);
        }

        $preferences = $customer->preferences ?? [];
        $preferred = $preferences['preferred_market_ids'] ?? [];

        if (in_array($market->id, $preferred)) {
            $preferred = array_values(array_diff($preferred, [$market->id]));
            $favorited = false;
            $message = "'{$market->name}' removed from preferred markets.";
        } else {
            $preferred[] = $market->id;
            $favorited = true;
            $message = "'{$market->name}' saved to preferred markets!";
        }

        $preferences['preferred_market_ids'] = $preferred;
        $customer->update(['preferences' => $preferences]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
