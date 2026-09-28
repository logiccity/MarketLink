@extends('layouts.farmer')

@section('title', 'Incoming Orders')
@section('page-title', 'Customer Pre-Orders')

@section('content')

{{-- Filter Tabs --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    @php
      $statusTabs = [
        'all' => ['label' => 'All Orders', 'count' => null],
        'PLACED' => ['label' => 'Pending', 'count' => $pendingCount ?? 0],
        'ACCEPTED' => ['label' => 'Accepted', 'count' => $acceptedCount ?? 0],
        'READY_FOR_PICKUP' => ['label' => 'Ready for Pickup', 'count' => $readyCount ?? 0],
        'COMPLETED' => ['label' => 'Completed', 'count' => null],
        'CANCELLED' => ['label' => 'Cancelled / Declined', 'count' => null],
      ];
      $activeStatus = request('status', 'all');
    @endphp

    @foreach($statusTabs as $val => $tab)
      <a href="{{ route('farmer.orders.index', ['status' => $val]) }}"
         class="btn btn-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2"
         style="{{ $activeStatus == $val
            ? 'background: var(--color-primary, #15803d); color: #FFF; font-weight: 700; border: none; font-size: 0.82rem;'
            : 'background: var(--color-surface, #FFF); border: 1px solid var(--color-border, #e2e8f0); color: var(--color-text-muted, #64748b); font-weight: 500; font-size: 0.82rem;' }}">
        <span>{{ $tab['label'] }}</span>
        @if($tab['count'] && $tab['count'] > 0)
          <span class="badge rounded-pill" style="background: {{ $val === 'PLACED' ? '#f59e0b' : '#3b82f6' }}; color: #FFF; font-size: 0.68rem; font-weight: 800;">
            {{ $tab['count'] }}
          </span>
        @endif
      </a>
    @endforeach
  </div>

  {{-- Pickup Date Filter --}}
  <form method="GET" action="{{ route('farmer.orders.index') }}" class="d-flex align-items-center gap-2">
    @if(request('status'))
      <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <input type="date" name="date" class="form-control form-control-sm rounded-pill" value="{{ request('date') }}" onchange="this.form.submit()">
    @if(request('date'))
      <a href="{{ route('farmer.orders.index', ['status' => request('status', 'all')]) }}" class="btn btn-sm btn-light border rounded-pill px-2" title="Clear date filter">
        <i class="bi bi-x"></i>
      </a>
    @endif
  </form>
</div>

{{-- Orders --}}
@forelse($orders as $order)
  @php
    $statusBadges = [
      'PLACED'           => ['class' => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle', 'label' => 'Pending Confirmation', 'icon' => 'hourglass-split'],
      'ACCEPTED'         => ['class' => 'bg-info-subtle text-info-emphasis border border-info-subtle', 'label' => 'Accepted', 'icon' => 'check2'],
      'READY_FOR_PICKUP' => ['class' => 'bg-primary-subtle text-primary border border-primary-subtle', 'label' => 'Ready for Pickup', 'icon' => 'box-seam'],
      'COMPLETED'        => ['class' => 'bg-success-subtle text-success border border-success-subtle', 'label' => 'Picked Up & Paid', 'icon' => 'check2-circle'],
      'DECLINED'         => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'label' => 'Declined', 'icon' => 'x-circle'],
      'CANCELLED'        => ['class' => 'bg-danger-subtle text-danger border border-danger-subtle', 'label' => 'Cancelled', 'icon' => 'x-circle'],
    ];
    $badge = $statusBadges[$order->status] ?? ['class' => 'bg-secondary-subtle text-secondary', 'label' => $order->status, 'icon' => 'circle'];
  @endphp

  <div class="order-row-lux mb-3 p-3 p-md-4 rounded-4 border bg-white shadow-sm">
    <div class="row g-3 align-items-center">
      {{-- Order Info --}}
      <div class="col-md-5">
        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
          <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.05rem;">
            #{{ $order->order_number }}
          </span>
          <span class="badge rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1 {{ $badge['class'] }}" style="font-size: 0.72rem; font-weight: 600;">
            <i class="bi bi-{{ $badge['icon'] }}"></i> {{ $badge['label'] }}
          </span>
        </div>

        <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
          <i class="bi bi-person me-1" style="color: var(--color-secondary);"></i>
          <strong style="color: var(--color-text-dark);">{{ $order->customer->user->name ?? 'Verified Patron' }}</strong>
          @if($order->customer?->user?->phone)
            <span class="text-muted ms-1">({{ $order->customer->user->phone }})</span>
          @endif
        </div>

        <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0.25rem;">
          <i class="bi bi-calendar3 me-1" style="color: var(--color-secondary);"></i>Placed {{ $order->created_at->format('d M Y, g:i A') }}
        </div>

        @if($order->pickup_date)
          <div style="font-size: 0.82rem; font-weight: 600; color: var(--color-success, #15803d);">
            <i class="bi bi-bag-check me-1"></i>Pickup: {{ \Carbon\Carbon::parse($order->pickup_date)->format('D, d M Y') }}
            @if($order->pickupSlot)
              <span class="text-dark fw-normal">({{ substr($order->pickupSlot->start_time, 0, 5) }} - {{ substr($order->pickupSlot->end_time, 0, 5) }})</span>
            @endif
          </div>
        @endif

        @if($order->market)
          <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 0.2rem;">
            <i class="bi bi-shop me-1"></i>{{ $order->market->name }}
          </div>
        @endif
      </div>

      {{-- Reserved Items + Total --}}
      <div class="col-md-4">
        <div class="rounded-3 p-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
          @php $orderItemList = $order->orderItems->isNotEmpty() ? $order->orderItems : $order->items; @endphp
          @foreach($orderItemList as $item)
            <div class="d-flex justify-content-between align-items-center py-1 border-bottom" style="font-size: 0.82rem; border-color: #f1f5f9 !important;">
              <div>
                <span class="text-muted me-1">•</span>
                <strong>{{ $item->product_name ?? $item->product_name_snapshot ?? ($item->product?->name ?? 'Produce Item') }}</strong>
                <span class="text-muted small">× {{ $item->quantity }} {{ $item->unit_snapshot ?? $item->unit ?? '' }}</span>
              </div>
              <span class="fw-semibold text-muted small">PKR {{ number_format($item->subtotal, 2) }}</span>
            </div>
          @endforeach
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2 px-1">
          <span class="small text-muted fw-semibold">Due at Stall:</span>
          <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success, #15803d); font-size: 1.15rem;">
            PKR {{ number_format($order->total ?? $order->total_amount ?? 0, 2) }}
          </span>
        </div>
      </div>

      {{-- Actions --}}
      <div class="col-md-3">
        <div class="d-flex flex-column gap-2">
          {{-- View Details --}}
          <a href="{{ route('farmer.orders.show', $order) }}" class="btn btn-sm rounded-pill py-2 text-center" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; font-weight: 600; font-size: 0.82rem;">
            <i class="bi bi-eye me-1"></i>View Details
          </a>

          {{-- Status-specific buttons --}}
          @if($order->status === 'PLACED')
            <form action="{{ route('farmer.orders.confirm', $order) }}" method="POST">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-sm rounded-pill py-2 w-100 text-white fw-bold shadow-sm" style="background: #15803d; border: none; font-size: 0.82rem;">
                <i class="bi bi-check-circle me-1"></i>Accept Pre-Order
              </button>
            </form>
          @elseif($order->status === 'ACCEPTED')
            <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="READY_FOR_PICKUP">
              <button type="submit" class="btn btn-sm rounded-pill py-2 w-100 text-white fw-bold shadow-sm" style="background: #2563eb; border: none; font-size: 0.82rem;">
                <i class="bi bi-box-seam me-1"></i>Mark Ready for Pickup
              </button>
            </form>
          @elseif($order->status === 'READY_FOR_PICKUP')
            <form action="{{ route('farmer.orders.status', $order) }}" method="POST" onsubmit="return confirm('Confirm customer picked up produce and paid in cash?');">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="COMPLETED">
              <button type="submit" class="btn btn-sm rounded-pill py-2 w-100 text-white fw-bold shadow-sm" style="background: #15803d; border: none; font-size: 0.82rem;">
                <i class="bi bi-cash-stack me-1"></i>Collected & Paid
              </button>
            </form>
          @elseif($order->status === 'COMPLETED')
            <span class="badge rounded-pill py-2 text-center text-success border border-success-subtle bg-success-subtle small fw-bold">
              <i class="bi bi-check2-all me-1"></i>Order Fulfilled
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>
@empty
  <div class="portal-card text-center p-5 rounded-4 border bg-white shadow-sm">
    <div style="font-size: 3.5rem; opacity: 0.3; margin-bottom: 0.75rem;">🧺</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.5rem;">No Pre-Orders Found</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">No reservations match the selected filter tab.</p>
  </div>
@endforelse

<div class="mt-4">{{ $orders->withQueryString()->links('pagination::bootstrap-5') }}</div>

@endsection
