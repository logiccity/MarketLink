<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return redirect()->route('admin.announcements.index', ['create' => 1]);
    }

    public function edit(Announcement $announcement)
    {
        return redirect()->route('admin.announcements.index', ['edit' => $announcement->id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target_role' => ['required', 'in:all,customer,farmer'],
            'status' => ['required', 'in:published,draft,archived'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
        ]);

        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        return back()->with('success', 'Announcement published.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target_role' => ['required', 'in:all,customer,farmer'],
            'status' => ['required', 'in:published,draft,archived'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $announcement->update($validated);

        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('success', 'Announcement removed.');
    }
}
