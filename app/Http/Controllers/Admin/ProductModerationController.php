<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['farmer.user', 'category', 'market']);

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('farmer', fn($f) => $f->where('stall_name', 'like', "%{$search}%"));
        }

        if ($status = $request->query('status')) {
            $query->where('availability_status', $status);
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function updateStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'availability_status' => ['required', 'in:available,low_stock,sold_out,temporarily_unavailable'],
        ]);

        $product->update($validated);

        return back()->with('success', 'Product listing status updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product listing removed by administrator.');
    }
}
