@extends('layouts.app')

@section('title', 'Browse Fresh Produce')
@section('meta_description', 'Browse seasonal fresh produce from local farmers. Pre-order for pickup at your community market.')

@section('content')
<div class="container-fluid px-0">
  <div class="row g-0">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="filter-sidebar sticky-top" style="top: 72px; padding: 1.5rem; background: #fff; border-right: 1px solid var(--border); min-height: 80vh;">
        <h5 class="fw-bold mb-4 text-dark">Filter Produce</h5>

        <form action="{{ route('products.index') }}" method="GET" id="filterForm">
          <!-- Category -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Category</h6>
            <div class="d-flex flex-column gap-1">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="category" id="cat_all" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                <label class="form-check-label small" for="cat_all">All Categories</label>
              </div>
              @foreach($categories as $cat)
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="category" id="cat_{{ $cat->id }}" value="{{ $cat->slug }}" {{ (request('category') == $cat->id || request('category') == $cat->slug) ? 'checked' : '' }} onchange="this.form.submit()">
                  <label class="form-check-label small d-inline-flex align-items-center gap-2" for="cat_{{ $cat->id }}">
                    <x-category-icon :category="$cat" size="0.72rem" />
                    <span>{{ $cat->name }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Price Range -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Price Range</h6>
            <div class="row g-2">
              <div class="col-6">
                <input type="number" name="min_price" class="form-control form-control-sm rounded-pill" placeholder="Min" value="{{ request('min_price') }}" min="0" step="0.01">
              </div>
              <div class="col-6">
                <input type="number" name="max_price" class="form-control form-control-sm rounded-pill" placeholder="Max" value="{{ request('max_price') }}" min="0" step="0.01">
              </div>
            </div>
          </div>

          <!-- Market -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Market</h6>
            <select name="market" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
              <option value="">All Markets</option>
              @foreach($markets as $market)
                <option value="{{ $market->id }}" {{ request('market') == $market->id ? 'selected' : '' }}>{{ $market->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- Market Day -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Market Day</h6>
            <select name="day" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
              <option value="">Any Day</option>
              @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $d)
                <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>🗓️ {{ $d }}</option>
              @endforeach
            </select>
          </div>

          <!-- Sort By -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Sort By</h6>
            <select name="sort" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
              <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
              <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
              <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
              <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
              <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
            </select>
          </div>

          <!-- Organic / Available -->
          <div class="mb-4">
            <h6 class="fw-semibold text-dark mb-2 small text-uppercase" style="letter-spacing: 0.06em;">Options</h6>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="organic" id="filter_organic" value="1" {{ request('organic') ? 'checked' : '' }} onchange="this.form.submit()">
              <label class="form-check-label small" for="filter_organic">🌿 Organic Only</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="in_stock" id="filter_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
              <label class="form-check-label small" for="filter_stock">✅ In Stock Only</label>
            </div>
          </div>

          <input type="hidden" name="search" value="{{ request('search') }}">

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-egreen btn-sm rounded-pill">Apply Filters</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Clear All</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="col-lg-9">
      <div class="p-3 p-md-4">
        <!-- Header Bar -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
          <div>
            <h1 class="h4 fw-bold text-dark mb-1">
              @if(request('search'))
                Results for "{{ request('search') }}"
              @elseif(!empty($activeCategoryName))
                {{ $activeCategoryName }}
              @else
                All Fresh Produce
              @endif
            </h1>
            <p class="text-muted small mb-0">{{ $products->total() }} products available</p>
          </div>
          <!-- Mobile Filter Trigger -->
          <button class="btn btn-outline-success btn-sm rounded-pill d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#mobileFilters">
            <i class="bi bi-funnel me-1"></i>Filters
          </button>
        </div>

        <!-- Search Bar (mobile) -->
        <form action="{{ route('products.index') }}" method="GET" class="mb-4">
          <div class="input-group rounded-pill overflow-hidden" style="border: 1px solid var(--border);">
            <span class="input-group-text bg-white border-0 ps-3 text-muted"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control border-0 py-2" placeholder="Search produce..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-egreen border-0 px-3">Search</button>
          </div>
        </form>

        @if($products->count() > 0)
          <div class="row g-2 g-sm-3 g-md-4">
            @foreach($products as $product)
              <div class="col-6 col-md-6 col-xl-4">
                <x-product-card :product="$product" />
              </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="mt-4">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
          </div>
        @else
          <div class="text-center py-5">
            <div style="font-size: 4rem; opacity: 0.4;">🌱</div>
            <h4 class="mt-4 fw-bold text-dark">No products found</h4>
            <p class="text-muted">Try adjusting your search or filters to find what you're looking for.</p>
            <a href="{{ route('products.index') }}" class="btn btn-egreen rounded-pill mt-2">Clear Filters</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Mobile Offcanvas Filters -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFilters" aria-labelledby="mobileFiltersLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title fw-bold" id="mobileFiltersLabel">Filter Produce</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form action="{{ route('products.index') }}" method="GET">
      <div class="mb-3">
        <label class="fw-semibold small text-dark mb-2">Category</label>
        <select name="category" class="form-select rounded-3">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->slug }}" {{ (request('category') == $cat->id || request('category') == $cat->slug) ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="fw-semibold small text-dark mb-2">Market</label>
        <select name="market" class="form-select rounded-3">
          <option value="">All Markets</option>
          @foreach($markets as $m)
            <option value="{{ $m->id }}" {{ request('market') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="fw-semibold small text-dark mb-2">Market Day</label>
        <select name="day" class="form-select rounded-3">
          <option value="">Any Day</option>
          @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $d)
            <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>🗓️ {{ $d }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <label class="fw-semibold small text-dark mb-2">Price Range</label>
        <div class="row g-2">
          <div class="col-6">
            <input type="number" name="min_price" class="form-control rounded-3" placeholder="Min PKR" value="{{ request('min_price') }}" min="0">
          </div>
          <div class="col-6">
            <input type="number" name="max_price" class="form-control rounded-3" placeholder="Max PKR" value="{{ request('max_price') }}" min="0">
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label class="fw-semibold small text-dark mb-2">Sort</label>
        <select name="sort" class="form-select rounded-3">
          <option value="latest">Newest</option>
          <option value="price_asc">Price Low–High</option>
          <option value="price_desc">Price High–Low</option>
          <option value="popular">Most Popular</option>
        </select>
      </div>
      <div class="mb-4">
        <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="organic" value="1" {{ request('organic') ? 'checked' : '' }}><label class="form-check-label small">Organic Only</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}><label class="form-check-label small">In Stock Only</label></div>
      </div>
      <button type="submit" class="btn btn-egreen w-100 rounded-pill">Apply Filters</button>
      <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-2">Clear All</a>
    </form>
  </div>
</div>

@endsection
