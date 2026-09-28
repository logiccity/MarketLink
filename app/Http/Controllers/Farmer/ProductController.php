<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use App\Models\ProductImage;
use App\Notifications\RestockAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $farmer = auth()->user()->farmer;
        $query = Product::where('farmer_id', $farmer->id)->with(['category', 'market', 'images']);

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            if ($status === 'active') {
                $query->where('availability_status', '!=', 'temporarily_unavailable')->where('quantity', '>', 0);
            } elseif ($status === 'inactive') {
                $query->where('availability_status', 'temporarily_unavailable');
            } elseif ($status === 'out_of_stock') {
                $query->where(function($q) {
                    $q->where('quantity', '<=', 0)->orWhere('availability_status', 'sold_out');
                });
            } else {
                $query->where('availability_status', $status);
            }
        }

        if ($categoryId = ($request->query('category') ?? $request->query('category_id'))) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('status', 'active')->orderBy('name')->get();

        return view('farmer.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $farmer = auth()->user()->farmer;
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $markets = $farmer->markets;

        return view('farmer.products.create', compact('categories', 'markets'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmer;

        if (!$request->has('quantity') && $request->has('stock_quantity')) {
            $request->merge(['quantity' => $request->input('stock_quantity')]);
        }

        if (!$request->has('availability_status')) {
            $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;
            $qty = (int) $request->input('quantity', 0);
            $defaultStatus = !$isActive ? 'temporarily_unavailable' : ($qty <= 0 ? 'sold_out' : ($qty <= 5 ? 'low_stock' : 'available'));
            $request->merge(['availability_status' => $defaultStatus]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'market_id' => ['nullable', 'exists:markets,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'in:kg,g,lb,bunch,head,each,dozen,bag,box,litre,jar,punnet,piece,basket,gram'],
            'quantity' => ['required', 'integer', 'min:0'],
            'default_weekly_quantity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'availability_status' => ['required', 'in:available,low_stock,sold_out,temporarily_unavailable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $validated['farmer_id'] = $farmer->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['default_weekly_quantity'] = $validated['default_weekly_quantity'] ?? $validated['quantity'];

        if ($validated['quantity'] <= 0 && $validated['availability_status'] !== 'temporarily_unavailable') {
            $validated['availability_status'] = 'sold_out';
        }

        $product = Product::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('farmer.products.index')->with('success', "Product '{$product->name}' created successfully.");
    }

    public function edit(Product $product)
    {
        $farmer = auth()->user()->farmer;
        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $markets = $farmer->markets;

        return view('farmer.products.edit', compact('product', 'categories', 'markets'));
    }

    public function update(Request $request, Product $product)
    {
        $farmer = auth()->user()->farmer;
        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        if (!$request->has('quantity') && $request->has('stock_quantity')) {
            $request->merge(['quantity' => $request->input('stock_quantity')]);
        }

        if (!$request->has('availability_status')) {
            $isActive = $request->has('is_active') ? $request->boolean('is_active') : ($product->availability_status !== 'temporarily_unavailable');
            $qty = (int) $request->input('quantity', $product->quantity);
            $defaultStatus = !$isActive ? 'temporarily_unavailable' : ($qty <= 0 ? 'sold_out' : ($qty <= 5 ? 'low_stock' : 'available'));
            $request->merge(['availability_status' => $defaultStatus]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'market_id' => ['nullable', 'exists:markets,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'unit' => ['required', 'string', 'in:kg,g,lb,bunch,head,each,dozen,bag,box,litre,jar,punnet,piece,basket,gram'],
            'quantity' => ['required', 'integer', 'min:0'],
            'default_weekly_quantity' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'availability_status' => ['required', 'in:available,low_stock,sold_out,temporarily_unavailable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $wasSoldOut = ($product->quantity <= 0 || $product->availability_status === 'sold_out');

        if ($validated['quantity'] <= 0 && $validated['availability_status'] !== 'temporarily_unavailable') {
            $validated['availability_status'] = 'sold_out';
        }

        $product->update($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            foreach ($product->images as $oldImg) {
                if ($oldImg->image_path && Storage::disk('public')->exists($oldImg->image_path)) {
                    Storage::disk('public')->delete($oldImg->image_path);
                }
                $oldImg->delete();
            }
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
            ]);
        }

        if ($wasSoldOut && $product->quantity > 0 && $product->availability_status !== 'sold_out') {
            foreach ($product->favoritedBy()->with('user')->get() as $cust) {
                $cust->user->notify(new RestockAlertNotification($product));
            }
        }

        return redirect()->route('farmer.products.index')->with('success', "Product '{$product->name}' updated successfully.");
    }

    public function destroy(Product $product)
    {
        $farmer = auth()->user()->farmer;
        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
            $img->delete();
        }

        $product->delete();

        return redirect()->route('farmer.products.index')->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $farmer = auth()->user()->farmer;
        if ($product->farmer_id !== $farmer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:available,sold_out,low_stock'],
        ]);

        $newStatus = $validated['status'];
        $wasSoldOut = ($product->availability_status === 'sold_out');

        $updates = ['availability_status' => $newStatus];
        if ($newStatus === 'sold_out') {
            $updates['quantity'] = 0;
        } elseif ($newStatus === 'available' && $product->quantity <= 0) {
            $updates['quantity'] = $product->default_weekly_quantity > 0 ? $product->default_weekly_quantity : 10;
        }

        $product->update($updates);

        if ($wasSoldOut && $newStatus === 'available' && $product->quantity > 0) {
            foreach ($product->favoritedBy()->with('user')->get() as $cust) {
                $cust->user->notify(new RestockAlertNotification($product));
            }
        }

        $label = match($newStatus) {
            'sold_out' => 'Sold Out',
            'low_stock' => 'Low Stock',
            default => 'Available',
        };

        return back()->with('success', "Item '{$product->name}' marked as {$label}.");
    }
}
