<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $customer = $user->customer;
        $familyMembers = $customer?->preferences['family_members'] ?? [];

        return view('customer.profile', compact('user', 'customer', 'familyMembers'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Add family member / household pickup authorization
     */
    public function addFamilyMember(Request $request)
    {
        $user = auth()->user();
        $customer = $user->customer;
        if (!$customer) {
            $customer = $user->customer()->create(['preferences' => []]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'authorized_for_pickup' => ['nullable', 'boolean'],
        ]);

        $preferences = $customer->preferences ?? [];
        $family = $preferences['family_members'] ?? [];

        $family[] = [
            'name' => $validated['name'],
            'relationship' => $validated['relationship'],
            'phone' => $validated['phone'],
            'authorized_for_pickup' => $request->boolean('authorized_for_pickup', true),
            'added_at' => now()->toDateTimeString(),
        ];

        $preferences['family_members'] = $family;
        $customer->update(['preferences' => $preferences]);

        return back()->with('success', "Family member '{$validated['name']}' added to household pickup access.");
    }

    /**
     * Remove family member
     */
    public function removeFamilyMember(Request $request, int $index)
    {
        $user = auth()->user();
        $customer = $user->customer;
        if (!$customer) {
            return back()->with('error', 'Customer record not found.');
        }

        $preferences = $customer->preferences ?? [];
        $family = $preferences['family_members'] ?? [];

        if (isset($family[$index])) {
            $removedName = $family[$index]['name'];
            array_splice($family, $index, 1);
            $preferences['family_members'] = $family;
            $customer->update(['preferences' => $preferences]);

            return back()->with('success', "'{$removedName}' removed from household access.");
        }

        return back()->with('error', 'Family member not found.');
    }
}
