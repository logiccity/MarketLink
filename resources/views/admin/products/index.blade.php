@extends('layouts.admin')

@section('title', 'Moderate Products')
@section('page-title', 'Produce Listings Moderation')

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.products.index') }}" class="portal-card mb-4" style="padding: 1.25rem 1.5rem;">
  <div class="row g-3 align-items-end">
    <div class="col-md-5">
      <label class="form-label-lux">Search</label>
      <div style="position: relative;">
        <i class="bi bi-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 0.9rem;"></i>
        <input type="text" name="search" class="form-control form-control-lux" placeholder="Product name or farmer stall..." value="{{ request('search') }}" style="padding-left: 2.5rem;">
      </div>
    </div>
    <div class="col-md-4">
      <label class="form-label-lux">Availability Status</label>
      <select name="status" class="form-select form-select-lux">
        <option value="">All Statuses</option>
        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
        <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
        <option value="sold_out" {{ request('status') === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
        <option value="temporarily_unavailable" {{ request('status') === 'temporarily_unavailable' ? 'selected' : '' }}>Temporarily Unavailable</option>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn rounded-pill py-2 flex-grow-1" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.86rem; border: none;">
        <i class="bi bi-funnel me-1"></i> Filter
      </button>
      @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.products.index') }}" class="btn rounded-pill px-3" style="background: var(--color-bg); border: 1.5px solid var(--color-border); color: var(--color-text-muted); font-size: 0.84rem;">
          <i class="bi bi-x"></i>
        </a>
      @endif
    </div>
  </div>
</form>

{{-- Products Table --}}
<div class="portal-card overflow-hidden">
  @if($products->isEmpty())
    <div class="text-center py-5" style="color: var(--color-text-muted);">
      <div style="font-size: 3rem; opacity: 0.25; margin-bottom: 0.75rem;">🥦</div>
      <p style="font-size: 0.9rem; margin: 0;">No produce listings found matching your criteria.</p>
    </div>
  @else
    <div class="table-responsive">
      <table class="table-lux table mb-0">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem;">Produce</th>
            <th>Category</th>
            <th>Farmer Stall</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $prod)
            <tr>
              <td style="padding-left: 1.5rem;">
                <div class="d-flex align-items-center gap-3">
                  <div style="width: 46px; height: 46px; border-radius: 10px; overflow: hidden; flex-shrink: 0; border: 1.5px solid var(--color-border); background: var(--color-sage-soft);">
                    <img src="{{ $prod->primary_image_url }}" alt="{{ $prod->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  <div>
                    <a href="{{ route('products.show', $prod) }}" target="_blank" style="font-family: var(--font-sans); font-weight: 600; color: var(--color-dark); font-size: 0.9rem; text-decoration: none;">
                      {{ $prod->name }}
                    </a>
                    @if($prod->is_featured)
                      <span style="font-size: 0.68rem; font-weight: 700; background: var(--color-warning-soft); color: #925D07; border: 1px solid rgba(216,155,61,0.3); border-radius: 100px; padding: 0.15rem 0.55rem; margin-left: 0.35rem;">★ Featured</span>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                <span style="font-size: 0.76rem; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.65rem; border-radius: 100px; font-weight: 600;">
                  {{ $prod->category->name ?? 'Uncategorized' }}
                </span>
              </td>
              <td>
                @if($prod->farmer)
                  <a href="{{ route('farmers.show', $prod->farmer) }}" target="_blank" style="font-size: 0.86rem; color: var(--color-secondary); font-weight: 600; text-decoration: none;">
                    {{ $prod->farmer->stall_name }}
                  </a>
                @else
                  <span style="font-size: 0.86rem; color: var(--color-text-muted);">No Farmer</span>
                @endif
              </td>
              <td>
                <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">PKR {{ number_format($prod->price, 2) }}</span>
                <span style="font-size: 0.78rem; color: var(--color-text-muted);"> / {{ $prod->unit }}</span>
              </td>
              <td>
                <span style="font-size: 0.88rem; font-weight: 700; color: {{ $prod->quantity <= 5 ? 'var(--color-danger)' : 'var(--color-dark)' }};">
                  {{ $prod->quantity }} {{ $prod->unit }}
                </span>
              </td>
              <td>
                <form action="{{ route('admin.products.status', $prod) }}" method="POST">
                  @csrf @method('PATCH')
                  <select name="availability_status" class="form-select form-select-sm rounded-pill" style="width: 148px; font-size: 0.76rem; font-weight: 600; border: 1.5px solid var(--color-border); padding: 0.3rem 0.75rem;" onchange="this.form.submit()">
                    <option value="available" {{ $prod->availability_status === 'available' ? 'selected' : '' }}>✓ Available</option>
                    <option value="low_stock" {{ $prod->availability_status === 'low_stock' ? 'selected' : '' }}>⚠ Low Stock</option>
                    <option value="sold_out" {{ $prod->availability_status === 'sold_out' ? 'selected' : '' }}>✗ Sold Out</option>
                    <option value="temporarily_unavailable" {{ $prod->availability_status === 'temporarily_unavailable' ? 'selected' : '' }}>— Unavailable</option>
                  </select>
                </form>
              </td>
              <td style="padding-right: 1.5rem;">
                <div class="d-flex justify-content-end gap-1">
                  <a href="{{ route('products.show', $prod) }}" target="_blank" class="btn btn-sm rounded-pill px-2" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted);" title="Preview Listing">
                    <i class="bi bi-box-arrow-up-right"></i>
                  </a>
                  <form action="{{ route('admin.products.destroy', $prod) }}" method="POST" onsubmit="return confirm('Remove this produce listing from MarketLink?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Delete Listing">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="admin-pagination-wrapper">
      {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@endsection
