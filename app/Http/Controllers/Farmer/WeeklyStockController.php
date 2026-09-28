<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WeeklyStockItem;
use App\Models\WeeklyStockTemplate;
use Illuminate\Http\Request;

class WeeklyStockController extends Controller
{
    public function index(Request $request)
    {
        $farmer = auth()->user()->farmer;
        $products = Product::where('farmer_id', $farmer->id)->with('category')->get();
        $templates = WeeklyStockTemplate::where('farmer_id', $farmer->id)->with('product')->get();

        $selectedDate = $request->query('date', now()->next('Saturday')->toDateString());
        $weeklyItems = WeeklyStockItem::where('farmer_id', $farmer->id)
            ->where('week_date', $selectedDate)
            ->with('product')
            ->get();

        return view('farmer.weekly-stock.index', compact('farmer', 'products', 'templates', 'selectedDate', 'weeklyItems'));
    }

    public function updateTemplate(Request $request)
    {
        $farmer = auth()->user()->farmer;

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'default_quantity' => ['required', 'integer', 'min:0'],
            'default_price' => ['required', 'numeric', 'min:0.01'],
            'is_available' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        WeeklyStockTemplate::updateOrCreate(
            ['farmer_id' => $farmer->id, 'product_id' => $validated['product_id']],
            [
                'default_quantity' => $validated['default_quantity'],
                'default_price' => $validated['default_price'],
                'is_available' => $request->boolean('is_available'),
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return back()->with('success', 'Weekly recurring template saved.');
    }

    public function applyTemplateToWeek(Request $request)
    {
        $farmer = auth()->user()->farmer;

        $validated = $request->validate([
            'week_date' => ['required', 'date'],
        ]);

        $templates = WeeklyStockTemplate::where('farmer_id', $farmer->id)->get();

        if ($templates->isEmpty()) {
            return back()->with('warning', 'Please configure your recurring templates first.');
        }

        foreach ($templates as $t) {
            WeeklyStockItem::updateOrCreate(
                [
                    'farmer_id' => $farmer->id,
                    'product_id' => $t->product_id,
                    'week_date' => $validated['week_date'],
                ],
                [
                    'template_id' => $t->id,
                    'quantity' => $t->default_quantity,
                    'price' => $t->default_price,
                    'availability_status' => $t->is_available ? ($t->default_quantity > 0 ? 'available' : 'sold_out') : 'temporarily_unavailable',
                ]
            );

            Product::where('id', $t->product_id)->update([
                'quantity' => $t->default_quantity,
                'price' => $t->default_price,
                'availability_status' => $t->is_available ? ($t->default_quantity > 0 ? 'available' : 'sold_out') : 'temporarily_unavailable',
            ]);
        }

        return back()->with('success', 'Recurring template applied to ' . $validated['week_date'] . ' and synchronized with active listings.');
    }

    public function updateItem(Request $request, WeeklyStockItem $item)
    {
        $farmer = auth()->user()->farmer;
        if ($item->farmer_id !== $farmer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'availability_status' => ['required', 'in:available,low_stock,sold_out,temporarily_unavailable'],
        ]);

        $item->update($validated);

        $item->product->update([
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'availability_status' => $validated['availability_status'],
        ]);

        return back()->with('success', "Stock for {$item->product->name} on {$item->week_date->format('M d')} updated.");
    }
}
