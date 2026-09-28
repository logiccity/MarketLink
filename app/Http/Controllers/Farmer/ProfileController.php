<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $farmer = auth()->user()->farmer;
        return view('farmer.profile', compact('farmer'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $farmer = $user->farmer;

        $validated = $request->validate([
            'stall_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1500'],
            'operating_days' => ['nullable', 'array'],
            'pickup_windows' => ['nullable', 'string', 'max:255'],
            'order_cutoff_hours' => ['required', 'integer', 'min:1', 'max:72'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $user->update([
            'name' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($farmer->profile_image && Storage::disk('public')->exists($farmer->profile_image)) {
                Storage::disk('public')->delete($farmer->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('farmers/profiles', 'public');
        } else {
            unset($validated['profile_image']);
        }

        if ($request->hasFile('banner_image')) {
            if ($farmer->banner_image && Storage::disk('public')->exists($farmer->banner_image)) {
                Storage::disk('public')->delete($farmer->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('farmers/banners', 'public');
        } else {
            unset($validated['banner_image']);
        }

        $validated['operating_days'] = $request->input('operating_days', []);

        $farmer->update($validated);

        return back()->with('success', 'Farmer profile updated successfully.');
    }
}
