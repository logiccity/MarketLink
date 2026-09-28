@php
  $inStock = $product->stock_quantity > 0;
  $isOrganic = $product->is_organic ?? false;
@endphp

<div class="product-card" role="article">
  <!-- Image Container with Badges -->
  <a href="{{ route('products.show', $product) }}" class="product-card-img text-decoration-none">
    @if($product->image)
      <img src="{{ str_starts_with($product->image, 'http') || str_starts_with($product->image, '/') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
    @else
      <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: var(--color-sage-soft); font-size: 3.2rem;">
        🌿
      </div>
    @endif

    <!-- Badges Overlay -->
    <div class="product-card-badges">
      @if($isOrganic)
        <span class="badge-organic">🌿 100% Organic</span>
      @endif
      @if(!$inStock)
        <span class="badge-stock out">Sold Out</span>
      @elseif($product->stock_quantity < 5)
        <span class="badge-stock low">⚡ Limited Harvest</span>
      @endif
    </div>

    <!-- Favorite Button -->
    @auth
      @if(auth()->user()->isCustomer())
        <button type="button"
          class="product-card-fav favorite-btn {{ auth()->user()->hasFavorited($product->id) ? 'active-fav' : '' }}"
          data-product-id="{{ $product->id }}"
          title="Save to Favorites"
          aria-label="Add to Favorites">
          <i class="bi bi-heart{{ auth()->user()->hasFavorited($product->id) ? '-fill text-danger' : '' }}"></i>
        </button>
      @endif
    @endauth
  </a>

  <!-- Product Details -->
  <div class="product-card-body">
    @if($product->category)
      <div class="product-category-tag">
        {{ $product->category->name }}
      </div>
    @endif

    <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
      <h3 class="product-card-title">{{ $product->name }}</h3>
    </a>

    @if($product->farmer)
      <div class="product-farmer-meta">
        <i class="bi bi-shop text-gold"></i>
        <a href="{{ route('farmers.show', $product->farmer) }}">
          {{ $product->farmer->stall_name }}
        </a>
      </div>
    @endif

    @if($product->average_rating)
      <div class="d-flex align-items-center gap-1 mb-3">
        @for($i = 1; $i <= 5; $i++)
          <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }} text-warning" style="font-size: 0.72rem;"></i>
        @endfor
        <span class="text-muted ms-1" style="font-size: 0.74rem; font-weight: 500;">
          {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count ?? 0 }})
        </span>
      </div>
    @endif

    <div class="d-flex align-items-end justify-content-between mt-auto pt-3 border-top" style="border-color: var(--color-border-subtle) !important;">
      <div>
        <span class="product-price-val">PKR {{ number_format($product->price, 2) }}</span>
        <span class="product-price-unit"> / {{ $product->unit }}</span>
      </div>

      @if($inStock)
        @auth
          @if(auth()->user()->isCustomer())
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form m-0">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="btn btn-lux-primary py-1 px-3" style="font-size: 0.8rem; border-radius: var(--radius-pill);" title="Add to Basket">
                <i class="bi bi-basket2"></i> Add
              </button>
            </form>
          @else
            <a href="{{ route('products.show', $product) }}" class="btn btn-lux-secondary py-1 px-3" style="font-size: 0.8rem; border-radius: var(--radius-pill);">
              View
            </a>
          @endif
        @else
          <a href="{{ route('products.show', $product) }}" class="btn btn-lux-primary py-1 px-3" style="font-size: 0.8rem; border-radius: var(--radius-pill);">
            <i class="bi bi-basket2"></i> Reserve
          </a>
        @endauth
      @else
        <span class="badge bg-secondary text-white py-1 px-3 rounded-pill" style="font-size: 0.75rem;">Unavailable</span>
      @endif
    </div>
  </div>
</div>
