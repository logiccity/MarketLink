<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['customer.user', 'farmer', 'product', 'order', 'responses']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,pending,hidden'],
        ]);

        $review->update($validated);

        return back()->with('success', 'Review moderation status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
