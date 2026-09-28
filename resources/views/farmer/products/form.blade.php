<div class="p-4" style="max-width: 850px;">

  <div class="mb-4">
    <a href="{{ route('farmer.products.index') }}" class="btn btn-light btn-sm rounded-pill border">
      <i class="bi bi-arrow-left me-1"></i>Back to Products
    </a>
  </div>

  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4 p-md-5">
      <form action="{{ isset($product) ? route('farmer.products.update', $product) : route('farmer.products.store') }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="row g-4">
          <!-- Product Name -->
          <div class="col-12">
            <label for="name" class="form-label fw-semibold text-dark">Product / Produce Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" placeholder="e.g. Organic Heritage Spinach, Crisp Red Apples, Raw Farm Honey..." required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Category + Market -->
          <div class="col-md-6">
            <label for="category_id" class="form-label fw-semibold text-dark">Produce Category <span class="text-danger">*</span></label>
            <select name="category_id" id="category_id" class="form-select rounded-3 @error('category_id') is-invalid @enderror" required>
              <option value="">Select a category</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="market_id" class="form-label fw-semibold text-dark">Designated Market Stall (Optional)</label>
            <select name="market_id" id="market_id" class="form-select rounded-3 @error('market_id') is-invalid @enderror">
              <option value="">All Market Locations</option>
              @if(isset($markets))
                @foreach($markets as $m)
                  <option value="{{ $m->id }}" {{ old('market_id', $product->market_id ?? '') == $m->id ? 'selected' : '' }}>
                    {{ $m->name }} ({{ $m->city ?? $m->location }})
                  </option>
                @endforeach
              @endif
            </select>
            @error('market_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Price + Unit -->
          <div class="col-md-6">
            <label for="price" class="form-label fw-semibold text-dark">Price (PKR) <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text rounded-start-3">PKR</span>
              <input type="number" name="price" id="price" class="form-control rounded-end-3 @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '') }}" placeholder="0.00" step="0.01" min="0.01" required>
              @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="col-md-6">
            <label for="unit" class="form-label fw-semibold text-dark">Unit of Measure <span class="text-danger">*</span></label>
            <select name="unit" id="unit" class="form-select rounded-3 @error('unit') is-invalid @enderror" required>
              @foreach([
                'kg' => 'Kilogram (kg)',
                'gram' => 'Gram (g)',
                'bunch' => 'Bunch',
                'dozen' => 'Dozen',
                'piece' => 'Piece / Each',
                'box' => 'Box',
                'basket' => 'Basket',
                'lb' => 'Pound (lb)',
                'head' => 'Head',
                'bag' => 'Bag',
                'litre' => 'Litre',
                'jar' => 'Jar',
                'punnet' => 'Punnet'
              ] as $val => $label)
                <option value="{{ $val }}" {{ old('unit', $product->unit ?? 'kg') == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Quantity + Availability Status -->
          <div class="col-md-6">
            <label for="quantity" class="form-label fw-semibold text-dark">Available Quantity / Stock <span class="text-danger">*</span></label>
            <input type="number" name="quantity" id="quantity" class="form-control rounded-3 @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity ?? 10) }}" placeholder="e.g. 50" min="0" required>
            <div class="form-text small text-muted">Items available for customer pre-order reservations.</div>
            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="availability_status" class="form-label fw-semibold text-dark">Availability Status <span class="text-danger">*</span></label>
            @php $currentStatus = old('availability_status', $product->availability_status ?? 'available'); @endphp
            <select name="availability_status" id="availability_status" class="form-select rounded-3 @error('availability_status') is-invalid @enderror" required>
              <option value="available" {{ $currentStatus === 'available' ? 'selected' : '' }}>🟢 Available (In Stock)</option>
              <option value="low_stock" {{ $currentStatus === 'low_stock' ? 'selected' : '' }}>🟡 Low Stock</option>
              <option value="sold_out" {{ $currentStatus === 'sold_out' ? 'selected' : '' }}>🔴 Sold Out</option>
              <option value="temporarily_unavailable" {{ $currentStatus === 'temporarily_unavailable' ? 'selected' : '' }}>⚪ Temporarily Unavailable (Hidden)</option>
            </select>
            @error('availability_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Description -->
          <div class="col-12">
            <label for="description" class="form-label fw-semibold text-dark">Produce Description & Growing Notes</label>
            <textarea name="description" id="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror" placeholder="Describe the harvest variety, organic cultivation notes, taste profile, and culinary uses...">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Image Upload -->
          <div class="col-12">
            <label for="image" class="form-label fw-semibold text-dark">Produce Photo</label>
            @if(isset($product) && $product->primary_image_url)
              <div class="mb-3 d-flex align-items-center gap-3">
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="rounded-3 border" style="height: 80px; width: 80px; object-fit: cover;">
                <div class="text-muted small">Current photo displayed to marketplace shoppers. Upload below to replace.</div>
              </div>
            @endif
            <input type="file" name="image" id="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
            <div class="form-text small text-muted">JPEG, PNG or WebP. Max 3MB. Square aspect ratio recommended.</div>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <!-- Options -->
          <div class="col-12">
            <div class="p-3 rounded-3 bg-light border d-flex flex-wrap gap-4 align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_organic" id="is_organic" value="1" {{ old('is_organic', $product->is_organic ?? false) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark small" for="is_organic">
                  🌿 100% Certified Organic / Pesticide-Free
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($product) ? ($product->availability_status !== 'temporarily_unavailable') : true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-dark small" for="is_active">
                  ✓ Active (Published to Public Stall)
                </label>
              </div>
            </div>
          </div>

          <!-- Submit -->
          <div class="col-12 d-flex gap-3 pt-3 border-top">
            <button type="submit" class="btn btn-egreen rounded-pill px-5 py-2 fw-bold text-white shadow-sm" style="background: var(--color-primary, #15803d);">
              <i class="bi bi-check2-circle me-1"></i>{{ isset($product) ? 'Update Product' : 'Publish Product' }}
            </button>
            <a href="{{ route('farmer.products.index') }}" class="btn btn-light border rounded-pill px-4 py-2">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
