@extends('layouts.customer')

@section('title', 'My Pre-Orders')
@section('page-title', 'My Pre-Orders')

@section('content')

{{-- Filters --}}
<form action="{{ route('customer.orders.index') }}" method="GET" class="portal-card mb-5" style="padding: 1.25rem 1.5rem;">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label-lux">Order Status</label>
      <select name="status" class="form-select form-select-lux">
        <option value="">All Orders</option>
        @foreach(['PLACED' => 'Placed', 'ACCEPTED' => 'Confirmed', 'DECLINED' => 'Declined', 'READY_FOR_PICKUP' => 'Ready for Pickup', 'COMPLETED' => 'Completed', 'CANCELLED' => 'Cancelled'] as $val => $label)
          <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Filter by Farmer</label>
      <select name="farmer" class="form-select form-select-lux">
        <option value="">All Farmers</option>
        @foreach($farmers as $f)
          <option value="{{ $f->id }}" {{ request('farmer') == $f->id ? 'selected' : '' }}>{{ $f->stall_name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Date From</label>
      <input type="date" name="from" class="form-control form-control-lux" value="{{ request('from') }}">
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn w-100 rounded-pill py-2" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.86rem; border: none;">
        <i class="bi bi-funnel me-1"></i> Filter
      </button>
    </div>
  </div>
</form>

{{-- Orders List --}}
@forelse($orders as $order)
  @php
    $statusMap = [
      'PLACED'           => ['cls' => 'badge-order-placed',    'icon' => 'hourglass-split',  'label' => 'Order Placed'],
      'ACCEPTED'         => ['cls' => 'badge-order-accepted',  'icon' => 'check-circle',     'label' => 'Confirmed'],
      'DECLINED'         => ['cls' => 'badge-order-cancelled', 'icon' => 'x-circle',         'label' => 'Declined'],
      'READY_FOR_PICKUP' => ['cls' => 'badge-order-ready',     'icon' => 'gift',             'label' => 'Ready for Pickup'],
      'COMPLETED'        => ['cls' => 'badge-order-completed', 'icon' => 'bag-check',        'label' => 'Completed'],
      'CANCELLED'        => ['cls' => 'badge-order-cancelled', 'icon' => 'x-circle',         'label' => 'Cancelled'],
    ];
    $sm = $statusMap[$order->status] ?? ['cls' => 'badge-order-placed', 'icon' => 'circle', 'label' => $order->status];
  @endphp

  <div class="portal-card mb-4">
    {{-- Card Header --}}
    <div class="portal-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1rem;">#{{ $order->order_number }}</span>
        <span class="badge-order-status {{ $sm['cls'] }}">
          <i class="bi bi-{{ $sm['icon'] }}"></i>{{ $sm['label'] }}
        </span>
      </div>
      <div style="font-size: 0.8rem; color: var(--color-text-muted);">
        <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, g:i A') }}
      </div>
    </div>

    {{-- Card Body --}}
    <div class="portal-card-body">
      <div class="row g-4">
        {{-- Order Items --}}
        <div class="col-md-7">
          <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.75rem;">Items Ordered</div>
          <div class="d-flex flex-column gap-2">
            @foreach($order->orderItems as $item)
              <div class="d-flex align-items-center gap-3">
                <div style="width: 50px; height: 50px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: var(--color-sage-soft); border: 1px solid var(--color-border);">
                  @if($item->product && $item->product->image)
                    <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🌿</div>
                  @endif
                </div>
                <div class="flex-grow-1">
                  <div style="font-size: 0.88rem; font-weight: 600; color: var(--color-dark);">{{ $item->product_name }}</div>
                  <div style="font-size: 0.78rem; color: var(--color-text-muted);">{{ $item->quantity }} × PKR {{ number_format($item->unit_price, 2) }}</div>
                </div>
                <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">PKR {{ number_format($item->subtotal, 2) }}</div>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Pickup Details --}}
        <div class="col-md-5">
          <div style="background: var(--color-sage-soft); border-radius: var(--radius-md); padding: 1.25rem; border: 1px solid rgba(168,201,160,0.3); height: 100%;">
            <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.85rem;">Pickup Details</div>
            @if($order->farmer)
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-shop" style="color: var(--color-secondary); font-size: 0.9rem;"></i>
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--color-dark);">{{ $order->farmer->stall_name }}</span>
              </div>
            @endif
            @if($order->pickup_date)
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-calendar-check" style="color: var(--color-secondary); font-size: 0.9rem;"></i>
                <span style="font-size: 0.85rem; color: var(--color-text);">{{ $order->pickup_date->format('D, d M Y') }}</span>
              </div>
            @endif
            @if($order->pickupSlot)
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-clock" style="color: var(--color-secondary); font-size: 0.9rem;"></i>
                <span style="font-size: 0.85rem; color: var(--color-text);">{{ $order->pickupSlot->start_time }} – {{ $order->pickupSlot->end_time }}</span>
              </div>
            @endif
            <div class="mt-3 pt-2" style="border-top: 1px solid rgba(168,201,160,0.4);">
              <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 1.2rem;">
                PKR {{ number_format($order->total_amount, 2) }}
              </div>
              <div style="font-size: 0.75rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em;">Cash at Pickup</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="d-flex flex-wrap gap-2 mt-4 pt-3" style="border-top: 1px solid var(--color-border-subtle);">
        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm rounded-pill px-4" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.82rem;">
          <i class="bi bi-eye me-1"></i>View Details
        </a>
        @if($order->status === 'PLACED')
          <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger); font-weight: 600; font-size: 0.82rem;" onclick="return confirm('Are you sure you want to cancel this order?')">
              <i class="bi bi-x-circle me-1"></i>Cancel Order
            </button>
          </form>
        @endif
        @if($order->status === 'COMPLETED' && !$order->review_submitted)
          <a href="{{ route('customer.orders.show', $order) }}#review-section" class="btn btn-sm rounded-pill px-4" style="background: var(--color-warning-soft); border: 1px solid rgba(216,155,61,0.35); color: #925D07; font-weight: 600; font-size: 0.82rem;">
            <i class="bi bi-star me-1"></i>Leave Review
          </a>
        @endif
        @if($order->status === 'COMPLETED')
          <form action="{{ route('customer.orders.reorder', $order) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.82rem;">
              <i class="bi bi-arrow-repeat me-1"></i>Reorder
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
@empty
  <div class="portal-card text-center" style="padding: 5rem 2rem;">
    <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;">📋</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">No Orders Yet</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 380px; margin: 0 auto 1.5rem;">Browse fresh local produce and place your first pre-order reservation.</p>
    <a href="{{ route('products.index') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.75rem; font-size: 0.88rem;">
      <i class="bi bi-basket2"></i> Browse Produce
    </a>
  </div>
@endforelse

<div class="mt-4">{{ $orders->withQueryString()->links('pagination::bootstrap-5') }}</div>

@endsection
