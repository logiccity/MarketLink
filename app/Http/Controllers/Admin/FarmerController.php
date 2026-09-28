<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = Farmer::with(['user', 'markets'])->withCount('products');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('stall_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('approval_status', $status);
        }

        $farmers = $query->latest()->paginate(12)->withQueryString();
        $pendingCount = Farmer::where('approval_status', 'pending')->count();

        return view('admin.farmers.index', compact('farmers', 'pendingCount'));
    }

    public function show(Farmer $farmer)
    {
        $farmer->load([
            'user',
            'markets',
            'products.category',
            'orders' => fn($q) => $q->latest()->take(10),
            'reviews.customer.user',
        ]);

        return view('admin.farmers.show', compact('farmer'));
    }

    public function updateStatus(Request $request, Farmer $farmer)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected,suspended,pending'],
        ]);

        $farmer->update(['approval_status' => $validated['status']]);
        if ($validated['status'] === 'suspended') {
            $farmer->user->update(['status' => 'suspended']);
        } elseif ($validated['status'] === 'approved' && $farmer->user->status === 'suspended') {
            $farmer->user->update(['status' => 'active']);
        }

        return back()->with('success', "Farmer status updated to {$validated['status']}.");
    }

    public function approve(Farmer $farmer)
    {
        $farmer->update(['approval_status' => 'approved']);
        $farmer->user->update(['status' => 'active']);
        return back()->with('success', "Farmer '{$farmer->stall_name}' has been approved.");
    }

    public function reject(Farmer $farmer)
    {
        $farmer->update(['approval_status' => 'rejected']);
        return back()->with('success', "Farmer '{$farmer->stall_name}' has been rejected.");
    }

    public function suspend(Farmer $farmer)
    {
        $farmer->update(['approval_status' => 'suspended']);
        $farmer->user->update(['status' => 'suspended']);
        return back()->with('success', "Farmer '{$farmer->stall_name}' has been suspended.");
    }

    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return back()->with('success', "Farmer '{$farmer->stall_name}' removed successfully.");
    }
}
