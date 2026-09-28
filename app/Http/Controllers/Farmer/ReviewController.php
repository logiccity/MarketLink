<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $farmer = auth()->user()->farmer;

        $query = Review::where('farmer_id', $farmer->id)
            ->with(['customer.user', 'product', 'order', 'responses']);

        $filter = $request->query('filter', 'all');

        if ($filter === 'pending_reply') {
            $query->doesntHave('responses');
        } elseif (in_array($filter, ['1', '2', '3', '4', '5'])) {
            $query->where('rating', (int) $filter);
        }

        $reviews = $query->latest()->paginate(10)->withQueryString();

        $allReviews = Review::where('farmer_id', $farmer->id)->get();
        $totalReviews = $allReviews->count();
        $ratingCounts = [
            5 => $allReviews->where('rating', 5)->count(),
            4 => $allReviews->where('rating', 4)->count(),
            3 => $allReviews->where('rating', 3)->count(),
            2 => $allReviews->where('rating', 2)->count(),
            1 => $allReviews->where('rating', 1)->count(),
        ];
        $pendingRepliesCount = Review::where('farmer_id', $farmer->id)->doesntHave('responses')->count();

        return view('farmer.reviews.index', compact('farmer', 'reviews', 'ratingCounts', 'totalReviews', 'pendingRepliesCount', 'filter'));
    }

    public function respond(Request $request, Review $review)
    {
        $farmer = auth()->user()->farmer;
        if ($review->farmer_id !== $farmer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'response' => ['required', 'string', 'min:3', 'max:1000'],
        ]);

        ReviewResponse::create([
            'review_id' => $review->id,
            'farmer_id' => $farmer->id,
            'response' => $validated['response'],
        ]);

        return back()->with('success', 'Your response has been published.');
    }
}
