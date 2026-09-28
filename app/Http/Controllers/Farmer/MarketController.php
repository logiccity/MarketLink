<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmer;
        $associatedMarkets = $farmer->markets()->withCount('products')->get();
        $availableMarkets = Market::where('status', 'active')
            ->whereNotIn('id', $associatedMarkets->pluck('id'))
            ->get();

        return view('farmer.markets.index', compact('farmer', 'associatedMarkets', 'availableMarkets'));
    }

    public function attach(Request $request)
    {
        $validated = $request->validate([
            'market_id' => ['required', 'exists:markets,id'],
            'stall_identifier' => ['nullable', 'string', 'max:100'],
        ]);

        $farmer = auth()->user()->farmer;

        if ($farmer->markets()->where('market_id', $validated['market_id'])->exists()) {
            return back()->with('info', 'Already associated with this market.');
        }

        $farmer->markets()->attach($validated['market_id'], [
            'stall_identifier' => $validated['stall_identifier'] ?? null,
        ]);

        return back()->with('success', 'Market associated successfully.');
    }

    public function detach(Market $market)
    {
        $farmer = auth()->user()->farmer;
        $farmer->markets()->detach($market->id);

        return back()->with('success', 'Disassociated from market.');
    }
}
