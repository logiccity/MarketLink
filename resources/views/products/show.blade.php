@extends('layouts.app')

@section('title', $product->name . ' — ' . ($product->farmer->stall_name ?? 'MarketLink'))

@section('content')
<div class="container py-5">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-success text-decoration-none">Produce</a></li>
      @if($product->category)
        <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="text-success text-decoration-none">{{ $product->category->name }}</a></li>
      @endif
      <li class="breadcrumb-item active text-muted" aria-current="page">{{ $product->name }}</li>
    </ol>
  </nav>

  <div class="row g-5">
    <!-- Product Images -->
    <div class="col-lg-5">
      <div class="product-detail-img rounded-4 overflow-hidden position-relative" style="border: 1px solid var(--border);">
        @if($product->image)
          <img src="{{ str_starts_with($product->image, 'http') || str_starts_with($product->image, '/') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100" style="height: 440px; object-fit: cover;">
        @else
          <div class="d-flex align-items-center justify-content-center" style="height: 440px; background: var(--cream);">
            <span style="font-size: 6rem; opacity: 0.5;">🌿</span>
          </div>
        @endif
        @if($product->is_organic)
          <span class="position-absolute top-0 start-0 m-3 badge bg-success rounded-pill px-3 py-2">🌿 Certified Organic</span>
        @endif
        @if($product->stock_quantity < 5 && $product->stock_quantity > 0)
          <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark rounded-pill px-3 py-2">⚡ Only {{ $product->stock_quantity }} left</span>
        @elseif($product->stock_quantity === 0)
          <span class="position-absolute top-0 end-0 m-3 badge bg-danger rounded-pill px-3 py-2">Out of Stock</span>
        @endif
      </div>
    </div>

    <!-- Product Info -->
    <div class="col-lg-7">
      @if($product->category)
        <div class="small text-success fw-semibold mb-2">{{ $product->category->name }}</div>
      @endif
      <h1 class="fw-bold text-dark mb-2" style="font-family: var(--font-heading); font-size: 2rem;">{{ $product->name }}</h1>

      <!-- Rating -->
      @if($product->average_rating)
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="d-flex gap-1 text-warning">
            @for($i = 1; $i <= 5; $i++)
              <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }} small"></i>
            @endfor
          </div>
          <span class="small text-muted">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count ?? 0 }} reviews)</span>
        </div>
      @endif

      <!-- Price -->
      <div class="product-detail-price mb-4">
        <span class="price-display">PKR {{ number_format($product->price, 2) }}</span>
        <span class="price-unit text-muted ms-2">/ {{ $product->unit }}</span>
      </div>

      <!-- Description -->
      <p class="text-muted lh-lg mb-4">{{ $product->description ?? 'Fresh seasonal produce from a local farm. Pre-order now to ensure your reserved portion is available on market day.' }}</p>

      <!-- Product Meta -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-sm-4">
          <div class="product-meta-card">
            <div class="text-muted small">Unit Size</div>
            <div class="fw-semibold">{{ $product->unit }}</div>
          </div>
        </div>
        <div class="col-6 col-sm-4">
          <div class="product-meta-card">
            <div class="text-muted small">Available</div>
            <div class="fw-semibold {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
              {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' ' . $product->unit : 'Out of Stock' }}
            </div>
          </div>
        </div>
        <div class="col-6 col-sm-4">
          <div class="product-meta-card">
            <div class="text-muted small">Season</div>
            <div class="fw-semibold">{{ $product->season ?? 'Year Round' }}</div>
          </div>
        </div>
        @if($product->harvest_date)
          <div class="col-6 col-sm-4">
            <div class="product-meta-card">
              <div class="text-muted small">Harvested</div>
              <div class="fw-semibold">{{ $product->harvest_date->format('d M Y') }}</div>
            </div>
          </div>
        @endif
        @if($product->cutoff_date)
          <div class="col-12 col-sm-8">
            <div class="product-meta-card" style="background: #fff8e1; border-color: #f9c74f;">
              <div class="text-muted small">⏰ Order Cutoff</div>
              <div class="fw-semibold text-warning">{{ $product->cutoff_date->format('D, d M Y g:i A') }}</div>
            </div>
          </div>
        @endif
      </div>

      <!-- Pickup Info -->
      @if($pickupSlots && $pickupSlots->count() > 0)
        <div class="mb-4 p-3 rounded-3 border" style="border-color: var(--sage) !important; background: var(--sage-light);">
          <div class="small fw-bold text-success mb-2"><i class="bi bi-clock me-2"></i>Available Pickup Slots</div>
          <div class="d-flex flex-wrap gap-2">
            @foreach($pickupSlots as $slot)
              <span class="badge rounded-pill px-3 py-2" style="background: #fff; color: var(--forest-green); border: 1px solid var(--sage); font-size: 0.8rem;">
                {{ $slot->market_day }}: {{ $slot->start_time }} – {{ $slot->end_time }}
              </span>
            @endforeach
          </div>
        </div>
      @endif

      <!-- Add to Cart -->
      @if($product->stock_quantity > 0)
        @auth
          @if(auth()->user()->isCustomer())
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <div class="d-flex gap-3 align-items-center mb-3">
                <div class="qty-control d-flex align-items-center gap-0 border rounded-pill overflow-hidden" style="width: 130px;">
                  <button type="button" class="btn btn-light border-0 px-3 py-2 qty-btn" data-action="decrease">–</button>
                  <input type="number" name="quantity" class="form-control border-0 text-center fw-bold qty-input" value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 50px;">
                  <button type="button" class="btn btn-light border-0 px-3 py-2 qty-btn" data-action="increase">+</button>
                </div>
                <button type="submit" class="btn btn-egreen rounded-pill px-5 py-2 fw-bold flex-grow-1">
                  <i class="bi bi-basket2-fill me-2"></i>Add to Basket
                </button>
              </div>
            </form>
            <!-- Favorite Button -->
            <button type="button" class="btn btn-outline-danger rounded-pill px-4 py-2 favorite-btn {{ auth()->user()->hasFavorited($product->id) ? 'active-fav' : '' }}" data-product-id="{{ $product->id }}">
              <i class="bi bi-heart{{ auth()->user()->hasFavorited($product->id) ? '-fill' : '' }} me-2"></i>
              {{ auth()->user()->hasFavorited($product->id) ? 'Saved to Favorites' : 'Save to Favorites' }}
            </button>
          @endif
        @else
          <div class="alert alert-info border-0 rounded-3">
            <a href="{{ route('login') }}" class="text-success fw-bold">Sign in</a> or
            <a href="{{ route('register') }}" class="text-success fw-bold">create an account</a> to pre-order this product.
          </div>
        @endauth
      @else
        <div class="alert alert-secondary border-0 rounded-3">
          <i class="bi bi-x-circle me-2 text-muted"></i>This product is currently out of stock. Check back next week.
        </div>
      @endif

      <hr>

      <!-- Farmer Info Card -->
      @if($product->farmer)
        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
          <div class="rounded-circle overflow-hidden" style="width: 56px; height: 56px; flex-shrink: 0; border: 2px solid var(--sage);">
            @if($product->farmer->profile_image)
              <img src="{{ str_starts_with($product->farmer->profile_image, 'http') ? $product->farmer->profile_image : asset('storage/' . $product->farmer->profile_image) }}" alt="{{ $product->farmer->stall_name }}" class="w-100 h-100" style="object-fit: cover;">
            @else
              <div class="w-100 h-100 bg-success d-flex align-items-center justify-content-center text-white fw-bold fs-4">
                {{ strtoupper(substr($product->farmer->stall_name, 0, 1)) }}
              </div>
            @endif
          </div>
          <div class="flex-grow-1">
            <div class="fw-bold text-dark">{{ $product->farmer->stall_name }}</div>
            <div class="text-muted small">Local Farm Stall &bull;
              @if($product->farmer->average_rating)
                <i class="bi bi-star-fill text-warning"></i>
                {{ number_format($product->farmer->average_rating, 1) }}
              @else
                New Farmer
              @endif
            </div>
          </div>
          <a href="{{ route('farmers.show', $product->farmer) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
            View Stall
          </a>
        </div>
      @endif
    </div>
  </div>

  <!-- Reviews Section -->
  <section class="mt-6 pt-5 border-top" id="reviews">
    <h3 class="fw-bold text-dark mb-4">Customer Reviews ({{ $reviews->total() }})</h3>

    @if($reviews->count() > 0)
      <div class="row g-4 mb-5">
        @foreach($reviews as $review)
          <div class="col-md-6">
            <div class="review-card p-4 rounded-4 border bg-white">
              <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 1rem; flex-shrink: 0;">
                  {{ strtoupper(substr($review->customer->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                  <div class="fw-semibold small">{{ $review->customer->user->name ?? 'Anonymous' }}</div>
                  <div class="text-muted" style="font-size: 0.75rem;">{{ $review->created_at->format('d M Y') }}</div>
                </div>
                <div class="ms-auto d-flex gap-1 text-warning">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" style="font-size: 0.8rem;"></i>
                  @endfor
                </div>
              </div>
              @if($review->comment)
                <p class="text-muted small mb-0 lh-lg">{{ $review->comment }}</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>
      {{ $reviews->links('pagination::bootstrap-5') }}
    @else
      <p class="text-muted">No reviews yet for this product.</p>
    @endif

    <!-- Leave a Review -->
    @auth
      @if(auth()->user()->isCustomer() && $canReview)
        @php
          // Find the most recent completed order from this farmer for the review route
          $reviewOrder = auth()->user()->customer
            ? \App\Models\Order::where('customer_id', auth()->user()->customer->id)
                ->where('farmer_id', $product->farmer_id)
                ->where('status', \App\Models\Order::STATUS_COMPLETED)
                ->latest()
                ->first()
            : null;
        @endphp
        @if($reviewOrder)
        <div class="mt-4 p-4 rounded-4 border bg-white">
          <h5 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning me-2"></i>Leave a Review</h5>
          <form action="{{ route('customer.reviews.store', $reviewOrder) }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="mb-3">
              <label class="fw-semibold small text-dark mb-2">Your Rating <span class="text-danger">*</span></label>
              <div class="star-rating-input d-flex gap-2" id="starRatingInput">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star star-input fs-4 text-muted" data-value="{{ $i }}" style="cursor: pointer;"></i>
                @endfor
                <input type="hidden" name="rating" id="ratingValue" value="" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="fw-semibold small text-dark mb-2">Your Review <span class="text-danger">*</span></label>
              <textarea name="comment" rows="3" class="form-control rounded-3" placeholder="Share your experience with this product..." required minlength="5" maxlength="1000"></textarea>
            </div>
            <button type="submit" class="btn btn-egreen rounded-pill px-4">
              <i class="bi bi-send me-2"></i>Submit Review
            </button>
          </form>
        </div>
        @endif
      @elseif(auth()->user()->isCustomer())
        <div class="p-3 rounded-3 border bg-light text-muted small mt-4">
          <i class="bi bi-info-circle me-2"></i>
          Complete a pickup order from this farmer's stall to leave a review.
        </div>
      @endif
    @endauth
  </section>

  <!-- Related Products -->
  @if($relatedProducts->count() > 0)
    <section class="mt-5 pt-5 border-top" id="related">
      <h3 class="fw-bold text-dark mb-4">You Might Also Like</h3>
      <div class="row g-4">
        @foreach($relatedProducts as $related)
          <div class="col-sm-6 col-lg-3">
            <x-product-card :product="$related" />
          </div>
        @endforeach
      </div>
    </section>
  @endif

</div>
@endsection

@push('scripts')
<script>
// Star rating input
document.querySelectorAll('.star-input').forEach(star => {
  star.addEventListener('click', function() {
    const val = this.dataset.value;
    document.getElementById('ratingValue').value = val;
    document.querySelectorAll('.star-input').forEach((s, i) => {
      s.className = i < val ? 'bi bi-star-fill star-input fs-4 text-warning' : 'bi bi-star star-input fs-4 text-muted';
      s.style.cursor = 'pointer';
    });
  });
  star.addEventListener('mouseenter', function() {
    const val = this.dataset.value;
    document.querySelectorAll('.star-input').forEach((s, i) => {
      s.className = i < val ? 'bi bi-star-fill star-input fs-4 text-warning' : 'bi bi-star star-input fs-4 text-muted';
    });
  });
});
document.getElementById('starRatingInput')?.addEventListener('mouseleave', function() {
  const val = document.getElementById('ratingValue').value;
  document.querySelectorAll('.star-input').forEach((s, i) => {
    s.className = i < val ? 'bi bi-star-fill star-input fs-4 text-warning' : 'bi bi-star star-input fs-4 text-muted';
  });
});
</script>
@endpush
