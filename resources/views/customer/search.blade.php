@extends('layouts.customer')

@section('title', 'Search — MarketLink')
@section('page-title', 'Search')

@section('content')

{{-- Search Bar --}}
<div class="portal-card mb-5" style="padding: 1.5rem 2rem;">
  <form action="{{ route('customer.search') }}" method="GET" id="customer-search-form">
    <div class="row g-3 align-items-end">
      <div class="col-md-5">
        <label class="form-label-lux">Search</label>
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0" style="border-color: var(--color-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
            <i class="bi bi-search" style="color: var(--color-text-muted);"></i>
          </span>
          <input
            type="text"
            name="q"
            id="search-input"
            class="form-control border-start-0"
            style="border-color: var(--color-border); border-radius: 0 var(--radius-md) var(--radius-md) 0;"
            placeholder="Search produce, farmers, markets…"
            value="{{ $query ?? '' }}"
            autofocus
          >
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label-lux">Search In</label>
        <select name="type" class="form-select form-select-lux">
          <option value="" {{ empty(request('type')) ? 'selected' : '' }}>Everything</option>
          <option value="products" {{ request('type') == 'products' ? 'selected' : '' }}>🥦 Products</option>
          <option value="farmers" {{ request('type') == 'farmers' ? 'selected' : '' }}>🌾 Farmers</option>
          <option value="markets" {{ request('type') == 'markets' ? 'selected' : '' }}>📍 Markets</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label-lux">Min Price</label>
        <input type="number" name="min_price" class="form-control form-control-lux" placeholder="PKR 0" value="{{ request('min_price') }}" min="0">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn w-100 rounded-pill py-2" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.88rem; border: none;">
          <i class="bi bi-search me-1"></i> Search
        </button>
      </div>
    </div>
  </form>
</div>

@if(!empty($query))

  {{-- Result Summary --}}
  <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <span style="font-size: 0.85rem; color: var(--color-text-muted);">
        Showing results for
      </span>
      <strong style="color: var(--color-dark);">"{{ $query }}"</strong>
      @php $total = ($products->total() ?? 0) + ($farmers->count() ?? 0) + ($markets->count() ?? 0); @endphp
      <span style="font-size: 0.83rem; color: var(--color-text-muted);">— {{ $total }} result(s) found</span>
    </div>
    <a href="{{ route('customer.search') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="bi bi-x me-1"></i> Clear
    </a>
  </div>

  {{-- Products Results --}}
  @if(empty(request('type')) || request('type') === 'products')
    @if($products->count() > 0)
      <div class="mb-5">
        <div class="portal-section-head">
          <h2 class="portal-section-title"><i class="bi bi-box-seam me-2" style="color: var(--color-secondary);"></i>Products ({{ $products->total() }})</h2>
          <a href="{{ route('products.index', ['search' => $query]) }}" class="portal-section-link">Browse All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3 mt-1">
          @foreach($products as $product)
            <div class="col-sm-6 col-lg-3">
              <div class="portal-card h-100 overflow-hidden p-0" style="border-radius: var(--radius-lg);">
                @php
                  $img = $product->image ? (str_starts_with($product->image, 'http') || str_starts_with($product->image, '/') ? $product->image : asset('storage/' . $product->image)) : null;
                @endphp
                @if($img)
                  <img src="{{ $img }}" alt="{{ $product->name }}" style="width: 100%; height: 140px; object-fit: cover;">
                @else
                  <div style="width: 100%; height: 140px; background: var(--color-sage-soft); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">🥦</div>
                @endif
                <div style="padding: 1rem;">
                  <div style="font-weight: 700; font-size: 0.9rem; color: var(--color-dark); margin-bottom: 0.3rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</div>
                  @if($product->farmer)
                    <div style="font-size: 0.76rem; color: var(--color-text-muted); margin-bottom: 0.5rem;">
                      <i class="bi bi-shop me-1"></i>{{ $product->farmer->stall_name }}
                    </div>
                  @endif
                  <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 1rem; margin-bottom: 0.75rem;">
                    PKR {{ number_format($product->price, 2) }}<span style="font-size: 0.72rem; color: var(--color-text-muted); font-family: var(--font-sans); font-weight: 400;">/{{ $product->unit }}</span>
                  </div>
                  <div class="d-flex gap-2">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm rounded-pill flex-grow-1" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.78rem;">View</a>
                    @auth
                      <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-sm rounded-pill" style="background: var(--color-primary); color: #FFF; font-size: 0.78rem;" title="Add to Basket">
                          <i class="bi bi-basket-plus"></i>
                        </button>
                      </form>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        @if($products->hasMorePages())
          <div class="mt-3 text-center">
            <a href="{{ route('products.index', ['search' => $query]) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
              View all {{ $products->total() }} products <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        @endif
      </div>
    @endif
  @endif

  {{-- Farmer Results --}}
  @if(empty(request('type')) || request('type') === 'farmers')
    @if($farmers->count() > 0)
      <div class="mb-5">
        <div class="portal-section-head">
          <h2 class="portal-section-title"><i class="bi bi-person-badge me-2" style="color: var(--color-secondary);"></i>Farmers ({{ $farmers->count() }})</h2>
          <a href="{{ route('farmers.index', ['search' => $query]) }}" class="portal-section-link">Browse All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3 mt-1">
          @foreach($farmers as $farmer)
            <div class="col-sm-6 col-lg-4">
              <div class="portal-card d-flex align-items-center gap-3">
                <div class="rounded-circle overflow-hidden flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white"
                     style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--color-secondary), var(--color-primary)); font-size: 1.4rem;">
                  @if($farmer->profile_image)
                    <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                  @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                  <div style="font-weight: 700; color: var(--color-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $farmer->stall_name }}</div>
                  @if($farmer->product_types)
                    <div style="font-size: 0.78rem; color: var(--color-text-muted);">{{ $farmer->product_types }}</div>
                  @endif
                  @if($farmer->markets->count())
                    <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-top: 0.2rem;">
                      <i class="bi bi-geo-alt me-1"></i>{{ $farmer->markets->take(2)->pluck('name')->implode(', ') }}
                    </div>
                  @endif
                </div>
                <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-sm rounded-pill flex-shrink-0" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.8rem;">
                  View
                </a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  @endif

  {{-- Market Results --}}
  @if(empty(request('type')) || request('type') === 'markets')
    @if($markets->count() > 0)
      <div class="mb-5">
        <div class="portal-section-head">
          <h2 class="portal-section-title"><i class="bi bi-shop me-2" style="color: var(--color-secondary);"></i>Markets ({{ $markets->count() }})</h2>
          <a href="{{ route('markets.index') }}" class="portal-section-link">Browse All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3 mt-1">
          @foreach($markets as $market)
            <div class="col-sm-6 col-lg-4">
              <div class="portal-card">
                <div style="font-weight: 700; color: var(--color-dark); font-size: 1rem; margin-bottom: 0.4rem;">{{ $market->name }}</div>
                @if($market->location ?? $market->address)
                  <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0.5rem;">
                    <i class="bi bi-geo-alt me-1 text-danger"></i>{{ $market->location ?? $market->address }}
                  </div>
                @endif
                @if($market->market_days)
                  <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach($market->market_days as $day)
                      <span class="badge" style="background: var(--color-sage-soft); color: var(--color-secondary); font-size: 0.72rem; border: 1px solid rgba(168,201,160,0.4);">🗓️ {{ $day }}</span>
                    @endforeach
                  </div>
                @endif
                <a href="{{ route('markets.show', $market) }}" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-size: 0.8rem; font-weight: 600; border: none;">View Market</a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  @endif

  {{-- No results --}}
  @if($total === 0)
    <div class="portal-card text-center" style="padding: 5rem 2rem;">
      <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;">🔍</div>
      <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">No Results Found</h5>
      <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 380px; margin: 0 auto 1.5rem;">
        We couldn't find anything matching "{{ $query }}". Try a different term or browse our full catalogue.
      </p>
      <div class="d-flex flex-wrap gap-2 justify-content-center">
        <a href="{{ route('products.index') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.5rem; font-size: 0.88rem;">
          <i class="bi bi-basket2"></i> Browse All Produce
        </a>
        <a href="{{ route('farmers.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4 py-2">
          <i class="bi bi-person-badge me-1"></i> All Farmers
        </a>
      </div>
    </div>
  @endif

@else

  {{-- Empty State: no query yet --}}
  <div class="portal-card text-center" style="padding: 5rem 2rem;">
    <div style="font-size: 4rem; margin-bottom: 1.25rem; opacity: 0.3;">🔍</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">Search Produce, Farmers & Markets</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 440px; margin: 0 auto;">
      Type in the search box above to find fresh seasonal products from local farmers, browse verified stalls, or locate your nearest pickup market.
    </p>
    <div class="row g-3 mt-4 justify-content-center" style="max-width: 600px; margin: 0 auto;">
      <div class="col-4 text-center">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🥦</div>
        <div style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark);">Products</div>
        <div style="font-size: 0.72rem; color: var(--color-text-muted);">Fresh seasonal produce</div>
      </div>
      <div class="col-4 text-center">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">🌾</div>
        <div style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark);">Farmers</div>
        <div style="font-size: 0.72rem; color: var(--color-text-muted);">Verified local growers</div>
      </div>
      <div class="col-4 text-center">
        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📍</div>
        <div style="font-size: 0.8rem; font-weight: 600; color: var(--color-dark);">Markets</div>
        <div style="font-size: 0.72rem; color: var(--color-text-muted);">Pickup locations near you</div>
      </div>
    </div>
  </div>

@endif

@endsection
