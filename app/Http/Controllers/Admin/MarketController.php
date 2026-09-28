<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketController extends Controller
{
    public function index()
    {
        $markets = Market::withCount(['farmers', 'products', 'orders'])->paginate(10);
        return view('admin.markets.index', compact('markets'));
    }

    public function create()
    {
        return view('admin.markets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'operating_days' => ['required', 'array'],
            'opening_time' => ['required', 'string'],
            'closing_time' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['latitude'] = $validated['latitude'] ?? 31.5204;
        $validated['longitude'] = $validated['longitude'] ?? 74.3587;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('markets', 'public');
        }

        Market::create($validated);

        return redirect()->route('admin.markets.index')->with('success', 'Market created successfully.');
    }

    public function show(Market $market)
    {
        $market->load([
            'farmers' => fn($q) => $q->withCount('products'),
            'products' => fn($q) => $q->with(['farmer', 'category'])->take(12),
            'orders' => fn($q) => $q->with(['customer.user', 'farmer'])->latest()->take(10),
        ]);

        return view('admin.markets.show', compact('market'));
    }

    public function edit(Market $market)
    {
        return view('admin.markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'operating_days' => ['required', 'array'],
            'opening_time' => ['required', 'string'],
            'closing_time' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:active,inactive'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if ($request->hasFile('image')) {
            if ($market->image && Storage::disk('public')->exists($market->image)) {
                Storage::disk('public')->delete($market->image);
            }
            $validated['image'] = $request->file('image')->store('markets', 'public');
        } else {
            unset($validated['image']);
        }

        $market->update($validated);

        return redirect()->route('admin.markets.index')->with('success', 'Market updated successfully.');
    }

    public function destroy(Market $market)
    {
        if ($market->image && Storage::disk('public')->exists($market->image)) {
            Storage::disk('public')->delete($market->image);
        }
        $market->delete();

        return redirect()->route('admin.markets.index')->with('success', 'Market removed.');
    }
}
