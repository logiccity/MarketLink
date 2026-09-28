<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Farmer;
use App\Models\Market;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = Farmer::where('approval_status', 'approved')
            ->with(['markets', 'user', 'products' => fn($q) => $q->where('availability_status', '!=', 'sold_out')]);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('stall_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($marketId = $request->query('market_id')) {
            $query->whereHas('markets', fn($q) => $q->where('markets.id', $marketId));
        }

        if ($day = $request->query('day')) {
            $query->whereJsonContains('operating_days', $day);
        }

        $farmers = $query->paginate(9)->withQueryString();
        $markets = Market::where('status', 'active')->orderBy('name')->get();

        return view('farmers.index', compact('farmers', 'markets'));
    }

    public function show(Farmer $farmer)
    {
        $user = auth()->user();
        $isAuthorized = $user && ($user->isAdmin() || $user->id === $farmer->user_id);

        if ($farmer->approval_status !== 'approved' && !$isAuthorized) {
            abort(404, 'Farmer profile is currently under review or inactive.');
        }

        $farmer->load([
            'markets',
            'products' => fn($q) => $q->with(['images', 'category']),
            'reviews' => fn($q) => $q->where('status', 'approved')->with(['customer.user', 'responses.farmer'])->latest(),
            'weeklyStockTemplates.product'
        ]);

        $customer = $user?->customer;
        $isFavorited = $customer ? $customer->hasFavoritedFarmer($farmer->id) : false;
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $products = $farmer->products()->with(['images', 'category'])->paginate(12)->withQueryString();
        $reviews = $farmer->reviews()
            ->where('status', 'approved')
            ->with(['customer.user', 'responses.farmer'])
            ->latest()
            ->paginate(5);

        return view('farmers.show', compact('farmer', 'isFavorited', 'categories', 'products', 'reviews'));
    }
}
