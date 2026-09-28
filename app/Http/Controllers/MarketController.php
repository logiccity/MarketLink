<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index(Request $request)
    {
        $query = Market::where('status', 'active')
            ->withCount(['farmers' => fn($q) => $q->where('approval_status', 'approved')]);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($day = $request->query('day')) {
            $query->whereJsonContains('operating_days', $day);
        }

        match ($request->query('sort', 'name_asc')) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'farmers_desc' => $query->orderBy('farmers_count', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        $markets = $query->paginate(9)->withQueryString();

        $mapMarkets = Market::where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('markets.index', compact('markets', 'mapMarkets'));
    }

    public function show(Market $market)
    {
        $market->load([
            'farmers' => fn($q) => $q->where('approval_status', 'approved')->with('user'),
            'products' => fn($q) => $q->where('availability_status', '!=', 'sold_out')
                ->with(['farmer', 'images', 'category'])
                ->take(12)
        ]);

        $farmers = $market->farmers;

        return view('markets.show', compact('market', 'farmers'));
    }
}
