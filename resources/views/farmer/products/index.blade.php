@extends('layouts.farmer')

@section('title', 'My Products')
@section('page-title', 'My Products')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-5">
  <div>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">
      <i class="bi bi-box-seam me-1"></i>{{ $products->total() }} products listed at your stall
    </p>
  </div>
  <a href="{{ route('farmer.products.create') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;">
    <i class="bi bi-plus-circle"></i> Add Product
  </a>
</div>

{{-- Filters --}}
<form action="{{ route('farmer.products.index') }}" method="GET" class="portal-card mb-4" style="padding: 1.25rem 1.5rem;">
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label class="form-label-lux">Search Products</label>
      <input type="text" name="search" class="form-control form-control-lux" placeholder="Product name..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Category</label>
      <select name="category" class="form-select form-select-lux">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Status</label>
      <select name="status" class="form-select form-select-lux">
        <option value="">All Status</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn w-100 rounded-pill py-2" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.86rem; border: none;">
        <i class="bi bi-funnel me-1"></i> Filter
      </button>
    </div>
  </div>
</form>

{{-- Products List --}}
@forelse($products as $product)
  <div class="order-row-lux">
    <div class="row align-items-center g-3">
      {{-- Image --}}
      <div class="col-auto">
        <div style="width: 72px; height: 72px; border-radius: 12px; overflow: hidden; flex-shrink: 0; border: 1.5px solid var(--color-border); background: var(--color-sage-soft);">
          @if($product->image)
            <img src="{{ str_starts_with($product->image, 'http') || str_starts_with($product->image, '/') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
          @else
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">🌿</div>
          @endif
        </div>
      </div>

      {{-- Info --}}
      <div class="col">
        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
          <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.05rem;">{{ $product->name }}</span>
          @if($product->is_organic)
            <span style="font-size: 0.68rem; font-weight: 700; background: var(--color-sage-soft); color: var(--color-secondary); border: 1px solid rgba(168,201,160,0.4); border-radius: 100px; padding: 0.18rem 0.65rem; letter-spacing: 0.04em; text-transform: uppercase;">🌿 Organic</span>
          @endif
          @if($product->is_active && $product->stock_quantity > 0)
            <span class="badge-order-status badge-order-ready" style="font-size: 0.68rem;">Active</span>
          @elseif(!$product->is_active)
            <span class="badge-order-status badge-order-cancelled" style="font-size: 0.68rem;">Inactive</span>
          @else
            <span class="badge-order-status badge-order-cancelled" style="font-size: 0.68rem;">Out of Stock</span>
          @endif
        </div>
        <div style="font-size: 0.82rem; color: var(--color-text-muted);">
          <span><i class="bi bi-tag me-1"></i>{{ $product->category->name ?? '—' }}</span>
          <span class="mx-2">·</span>
          <span style="font-weight: 700; color: var(--color-secondary);">PKR {{ number_format($product->price, 2) }} / {{ $product->unit }}</span>
          <span class="mx-2">·</span>
          <span><i class="bi bi-box me-1"></i>{{ $product->stock_quantity }} {{ $product->unit }} in stock</span>
        </div>
        @if($product->cutoff_date)
          <div style="font-size: 0.78rem; color: var(--color-warning); font-weight: 600; margin-top: 0.25rem;">
            <i class="bi bi-clock me-1"></i>Cutoff: {{ $product->cutoff_date->format('D d M, g:i A') }}
          </div>
        @endif
      </div>

      {{-- Quick Stats --}}
      <div class="col-auto d-none d-md-block text-center" style="padding: 0 1rem; border-left: 1px solid var(--color-border-subtle);">
        <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.15rem;">{{ $product->orders_count ?? 0 }}</div>
        <div style="font-size: 0.7rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700;">Orders</div>
      </div>
      <div class="col-auto d-none d-md-block text-center" style="padding: 0 1rem; border-left: 1px solid var(--color-border-subtle);">
        @if($product->average_rating)
          <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.15rem;">{{ number_format($product->average_rating, 1) }} ★</div>
          <div style="font-size: 0.7rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700;">Rating</div>
        @else
          <div style="font-size: 0.9rem; color: var(--color-text-light);">—</div>
        @endif
      </div>

      {{-- Actions --}}
      <div class="col-auto">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          {{-- Quick Status Toggle --}}
          <div class="dropdown">
            <button class="btn btn-sm rounded-pill px-3 dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: #f8fafc; border: 1px solid rgba(0,0,0,0.12); font-size: 0.78rem; font-weight: 600;">
              @if($product->availability_status === 'sold_out' || $product->quantity <= 0)
                <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">Sold Out</span>
              @elseif($product->availability_status === 'low_stock' || $product->quantity <= 5)
                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;">Low Stock</span>
              @else
                <span class="badge bg-success rounded-pill" style="font-size: 0.65rem;">Available</span>
              @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 small">
              <li><h6 class="dropdown-header text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">Set Availability</h6></li>
              <li>
                <form action="{{ route('farmer.products.toggle-status', $product) }}" method="POST">
                  @csrf @method('PATCH')
                  <input type="hidden" name="status" value="available">
                  <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 {{ $product->availability_status === 'available' ? 'fw-bold text-success' : '' }}">
                    <i class="bi bi-check-circle-fill text-success"></i> Mark Available
                  </button>
                </form>
              </li>
              <li>
                <form action="{{ route('farmer.products.toggle-status', $product) }}" method="POST">
                  @csrf @method('PATCH')
                  <input type="hidden" name="status" value="low_stock">
                  <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 {{ $product->availability_status === 'low_stock' ? 'fw-bold text-warning' : '' }}">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i> Mark Low Stock
                  </button>
                </form>
              </li>
              <li>
                <form action="{{ route('farmer.products.toggle-status', $product) }}" method="POST">
                  @csrf @method('PATCH')
                  <input type="hidden" name="status" value="sold_out">
                  <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 {{ $product->availability_status === 'sold_out' ? 'fw-bold text-danger' : '' }}">
                    <i class="bi bi-x-circle-fill text-danger"></i> Mark Sold Out
                  </button>
                </form>
              </li>
            </ul>
          </div>

          <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.8rem;">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger); font-size: 0.8rem;" onclick="return confirm('Delete this product?')">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@empty
  <div class="portal-card text-center" style="padding: 4rem 2rem;">
    <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;">📦</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">No Products Listed Yet</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 400px; margin: 0 auto 1.5rem;">Start listing your fresh produce so customers can find and pre-order from your stall.</p>
    <a href="{{ route('farmer.products.create') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.5rem; font-size: 0.88rem;">
      <i class="bi bi-plus-circle"></i> Add Your First Product
    </a>
  </div>
@endforelse

<div class="mt-4">{{ $products->withQueryString()->links('pagination::bootstrap-5') }}</div>

@endsection
