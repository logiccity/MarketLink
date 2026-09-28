@extends('layouts.customer')

@section('title', 'Favorites & Follows')
@section('page-title', 'My Favorites & Followed Farmers')

@section('content')
<div class="p-4">

  {{-- Preferred Markets --}}
  <div class="mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Preferred Markets & Pickup Hubs</h5>
      <a href="{{ route('markets.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Explore All Markets</a>
    </div>

    @if($favoriteMarkets->isEmpty())
      <div class="bg-white rounded-4 border shadow-sm p-4 text-center text-muted">
        <span style="font-size:2rem;">📍</span>
        <p class="mt-2 mb-1 fw-medium">No preferred markets saved yet.</p>
        <p class="small text-muted mb-3">Save your local neighborhood markets to get one-click route directions and pickup timings.</p>
        <a href="{{ route('markets.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">Browse Markets</a>
      </div>
    @else
      <div class="row g-3">
        @foreach($favoriteMarkets as $market)
          <div class="col-md-6 col-lg-4">
            <div class="bg-white rounded-4 border shadow-sm p-4 d-flex flex-column justify-content-between h-100">
              <div>
                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                  <h6 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">{{ $market->name }}</h6>
                  <form action="{{ route('customer.favorites.market.toggle', $market) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove from preferred markets">
                      <i class="bi bi-heart-fill"></i>
                    </button>
                  </form>
                </div>

                <div class="text-muted small mb-2">
                  <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $market->location ?? $market->address }}
                </div>

                @if($market->market_days)
                  <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach($market->market_days as $day)
                      <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">🗓️ {{ $day }}</span>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="pt-3 border-top d-flex gap-2">
                <a href="{{ route('markets.show', $market) }}" class="btn btn-sm btn-outline-success rounded-pill flex-grow-1">
                  View Market
                </a>
                @if($market->latitude && $market->longitude)
                  <a href="https://www.google.com/maps/dir/?api=1&destination={{ $market->latitude }},{{ $market->longitude }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary rounded-pill d-inline-flex align-items-center gap-1 px-3" title="Get Directions">
                    <i class="bi bi-compass"></i>
                    <span>Directions</span>
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Favorite Farmers --}}
  <div class="mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-person-heart text-success me-2"></i>Followed Farmers</h5>
    @if($favoriteFarmers->isEmpty())
      <div class="bg-white rounded-4 border shadow-sm p-5 text-center text-muted">
        <span style="font-size:2.5rem;">🌱</span>
        <p class="mt-2 mb-0">You haven't followed any farmers yet.</p>
        <a href="{{ route('farmers.index') }}" class="btn btn-egreen rounded-pill px-4 mt-3">Browse Farmers</a>
      </div>
    @else
      <div class="row g-3">
        @foreach($favoriteFarmers as $farmer)
        <div class="col-sm-6 col-lg-4">
          <div class="bg-white rounded-4 border shadow-sm p-4 d-flex align-items-center gap-3 h-100">
            <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center fw-bold text-success flex-shrink-0"
                 style="width:52px; height:52px; font-size:1.3rem; background: var(--color-success-soft, rgba(20,83,45,0.08)); border: 1.5px solid rgba(0,0,0,0.05);">
              @if($farmer->profile_image)
                <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
              @endif
            </div>
            <div class="flex-grow-1 overflow-hidden">
              <div class="fw-bold text-truncate">{{ $farmer->stall_name }}</div>
              <div class="text-muted small">{{ $farmer->product_types ?? 'Fresh Produce' }}</div>
              <div class="text-muted" style="font-size:.75rem;">
                @foreach($farmer->markets->take(2) as $m)
                  <span class="badge bg-light text-dark border me-1" style="font-size:.7rem;">{{ $m->name }}</span>
                @endforeach
              </div>
            </div>
            <div class="d-flex flex-column gap-1">
              <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-sm btn-outline-success rounded-pill">View</a>
              <form action="{{ route('customer.favorites.farmer.toggle', $farmer) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill w-100">Unfollow</button>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    @endif
  </div>

  {{-- Favorite Products --}}
  <div>
    <h5 class="fw-bold mb-3"><i class="bi bi-heart-fill text-danger me-2"></i>Saved Products</h5>
    @if($favoriteProducts->isEmpty())
      <div class="bg-white rounded-4 border shadow-sm p-5 text-center text-muted">
        <span style="font-size:2.5rem;">🛒</span>
        <p class="mt-2 mb-0">No saved products yet. Heart a product to save it!</p>
        <a href="{{ route('products.index') }}" class="btn btn-egreen rounded-pill px-4 mt-3">Browse Products</a>
      </div>
    @else
      <div class="row g-3">
        @foreach($favoriteProducts as $product)
        <div class="col-sm-6 col-lg-3">
          <div class="bg-white rounded-4 border shadow-sm overflow-hidden h-100 d-flex flex-column">
            @if($product->image)
              <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}" class="w-100" style="height:140px; object-fit:cover;">
            @else
              <div class="w-100 bg-light d-flex align-items-center justify-content-center" style="height:140px; font-size:2.5rem;">
                🥦
              </div>
            @endif
            <div class="p-3 flex-grow-1 d-flex flex-column">
              <div class="fw-bold small mb-1">{{ $product->name }}</div>
              <div class="text-muted" style="font-size:.75rem;">{{ $product->farmer->stall_name ?? '—' }}</div>
              <div class="text-success fw-bold mt-auto mb-2">PKR {{ number_format($product->price ?? $product->price_per_unit ?? 0, 2) }}/{{ $product->unit }}</div>
              <div class="d-flex gap-1">
                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-egreen rounded-pill flex-grow-1">View</a>
                <form action="{{ route('customer.favorites.product.toggle', $product) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                    <i class="bi bi-heart-fill"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="mt-4">
        {{ $favoriteProducts->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
