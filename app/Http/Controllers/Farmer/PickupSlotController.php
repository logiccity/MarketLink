<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\PickupSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PickupSlotController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmer;
        $slots = PickupSlot::where('farmer_id', $farmer->id)
            ->with(['market', 'orders'])
            ->orderBy('pickup_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(12);

        $markets = $farmer->markets;

        return view('farmer.pickup-slots.index', compact('slots', 'markets', 'farmer'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmer;

        $validated = $request->validate([
            'market_id' => ['required', 'exists:markets,id'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'cutoff_hours' => ['nullable', 'integer', 'min:1', 'max:72'],
        ]);

        $cutoffHours = $validated['cutoff_hours'] ?? $farmer->order_cutoff_hours ?? 12;
        $slotDateTime = Carbon::parse($validated['pickup_date'] . ' ' . $validated['start_time']);
        $cutoffTime = (clone $slotDateTime)->subHours($cutoffHours);

        PickupSlot::create([
            'farmer_id' => $farmer->id,
            'market_id' => $validated['market_id'],
            'pickup_date' => $validated['pickup_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'capacity' => $validated['capacity'],
            'cutoff_time' => $cutoffTime,
            'is_active' => true,
        ]);

        return back()->with('success', 'Pickup window slot created successfully.');
    }

    public function toggleActive(PickupSlot $slot)
    {
        $farmer = auth()->user()->farmer;
        if ($slot->farmer_id !== $farmer->id) {
            abort(403);
        }

        $slot->update(['is_active' => !$slot->is_active]);

        return back()->with('success', 'Pickup slot status toggled.');
    }

    public function destroy(PickupSlot $slot)
    {
        $farmer = auth()->user()->farmer;
        if ($slot->farmer_id !== $farmer->id) {
            abort(403);
        }

        if ($slot->booked_count > 0) {
            return back()->with('error', 'Cannot delete slot with active customer bookings.');
        }

        $slot->delete();

        return back()->with('success', 'Pickup slot deleted.');
    }
}
