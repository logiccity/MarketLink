@extends('layouts.customer')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="p-4" style="max-width: 900px;">

  <div class="mb-4">
    <a href="{{ route('customer.orders.index') }}" class="btn btn-light btn-sm rounded-pill border">
      <i class="bi bi-arrow-left me-1"></i>Back to Orders
    </a>
  </div>

  <!-- Status Timeline -->
  @php
    $statuses = ['PLACED', 'ACCEPTED', 'READY_FOR_PICKUP', 'COMPLETED'];
    $currentIndex = array_search($order->status, $statuses);
    $isCancelled = in_array($order->status, ['CANCELLED', 'DECLINED']);
  @endphp

  <div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold text-dark mb-0">Order #{{ $order->order_number }}</h5>
        @if($isCancelled)
          <span class="badge bg-danger rounded-pill px-3 py-2">{{ $order->status === 'CANCELLED' ? '❌ Cancelled' : '⚠️ No Show' }}</span>
        @endif
      </div>

      @if(!$isCancelled)
        <div class="order-timeline d-flex justify-content-between position-relative">
          <div class="timeline-track position-absolute" style="top: 20px; left: 0; right: 0; height: 3px; background: #e5e7eb; z-index: 0;">
            <div style="height: 100%; background: var(--emerald); width: {{ $currentIndex >= 0 ? ($currentIndex / (count($statuses)-1)) * 100 : 0 }}%; transition: width 0.5s;"></div>
          </div>
          @foreach($statuses as $i => $status)
            @php $done = $currentIndex !== false && $i <= $currentIndex; @endphp
            <div class="text-center position-relative" style="z-index: 1; flex: 1;">
              <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px; background: {{ $done ? 'var(--emerald)' : '#e5e7eb' }}; color: {{ $done ? '#fff' : '#9ca3af' }}; font-size: 1rem; border: 3px solid {{ $done ? 'var(--emerald)' : '#e5e7eb' }}; transition: all 0.3s;">
                @if($done)<i class="bi bi-check-lg"></i>@else<i class="bi bi-circle"></i>@endif
              </div>
              <div class="mt-2 small fw-semibold" style="color: {{ $done ? 'var(--forest-green)' : '#9ca3af' }}; font-size: 0.75rem;">
                {{ ['PLACED' => 'Placed', 'ACCEPTED' => 'Confirmed', 'READY_FOR_PICKUP' => 'Ready', 'COMPLETED' => 'Completed'][$status] ?? $status }}
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-danger border-0 rounded-3 small mb-0">
          <i class="bi bi-exclamation-circle me-2"></i>
          This order was {{ $order->status === 'CANCELLED' ? 'cancelled' : 'declined by the farmer' }}.
          @if($order->decline_reason)
            Reason: {{ $order->decline_reason }}
          @elseif($order->cancellation_reason)
            Reason: {{ $order->cancellation_reason }}
          @endif
        </div>
      @endif
    </div>
  </div>

  <div class="row g-4">
    <!-- Order Items -->
    <div class="col-md-7">
      <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
          <h6 class="fw-bold text-dark">Items Ordered</h6>
        </div>
        <div class="card-body px-4 pb-4">
          @foreach($order->orderItems as $item)
            <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 64px; height: 64px;">
                @if($item->product && $item->product->image)
                  <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" class="w-100 h-100" style="object-fit: cover;">
                @else
                  <div class="w-100 h-100 bg-success-subtle d-flex align-items-center justify-content-center" style="font-size: 1.5rem;">🌿</div>
                @endif
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                <div class="text-muted" style="font-size: 0.78rem;">{{ $item->quantity }} × PKR {{ number_format($item->unit_price, 2) }}</div>
                @if($item->product)
                  <a href="{{ route('products.show', $item->product) }}" class="text-success small text-decoration-none">View Product</a>
                @endif
              </div>
              <div class="fw-bold text-dark">PKR {{ number_format($item->subtotal, 2) }}</div>
            </div>
          @endforeach

          <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
            <span class="fw-bold text-dark">Total to Pay at Pickup</span>
            <span class="fw-bold text-success fs-5">PKR {{ number_format($order->total_amount, 2) }}</span>
          </div>
        </div>
      </div>

      @if($order->customer_notes)
        <div class="card border-0 rounded-4 shadow-sm mb-4">
          <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-2">Your Notes to Farmer</h6>
            <p class="text-muted small mb-0">{{ $order->customer_notes }}</p>
          </div>
        </div>
      @endif
    </div>

    <!-- Pickup Info -->
    <div class="col-md-5">
      <!-- Farmer Info -->
      @if($order->farmer)
        <div class="card border-0 rounded-4 shadow-sm mb-4">
          <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shop text-success me-2"></i>Farm Stall</h6>
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 50px; height: 50px;">
                @if($order->farmer->profile_image)
                  <img src="{{ str_starts_with($order->farmer->profile_image, 'http') ? $order->farmer->profile_image : asset('storage/' . $order->farmer->profile_image) }}" alt="{{ $order->farmer->stall_name }}" class="w-100 h-100" style="object-fit: cover;">
                @else
                  <div class="w-100 h-100 bg-success d-flex align-items-center justify-content-center text-white fw-bold">
                    {{ strtoupper(substr($order->farmer->stall_name, 0, 1)) }}
                  </div>
                @endif
              </div>
              <div>
                <div class="fw-semibold text-dark">{{ $order->farmer->stall_name }}</div>
                @if($order->farmer->phone)
                  <div class="text-muted small">{{ $order->farmer->phone }}</div>
                @endif
                <a href="{{ route('farmers.show', $order->farmer) }}" class="text-success small">View Stall</a>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- Pickup Details -->
      <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
          <h6 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-event text-success me-2"></i>Pickup Details</h6>
          @if($order->pickup_date)
            <div class="d-flex gap-3 align-items-center mb-3">
              <div class="text-center rounded-3 p-2" style="background: var(--sage-light); min-width: 56px;">
                <div class="fw-bold text-success" style="font-size: 1.5rem; line-height: 1;">{{ $order->pickup_date->format('d') }}</div>
                <div class="text-muted small">{{ $order->pickup_date->format('M Y') }}</div>
              </div>
              <div>
                <div class="fw-semibold text-dark">{{ $order->pickup_date->format('l, d F Y') }}</div>
                @if($order->pickupSlot)
                  <div class="text-muted small">{{ $order->pickupSlot->start_time }} – {{ $order->pickupSlot->end_time }}</div>
                @endif
              </div>
            </div>
          @else
            <p class="text-muted small">Pickup date to be confirmed by farmer.</p>
          @endif

          <div class="alert border-0 rounded-3 small p-3 mb-0" style="background: var(--gold-light); color: #7a5c1e;">
            <i class="bi bi-cash-coin me-2"></i>
            <strong>Bring ${{ number_format($order->total_amount, 2) }} cash</strong> to pay the farmer directly at their stall.
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="d-flex flex-column gap-2">
        @if($order->status === 'PLACED')
          <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2" onclick="return confirm('Cancel this order?')">
              <i class="bi bi-x-circle me-2"></i>Cancel Order
            </button>
          </form>
        @endif
        @if($order->status === 'COMPLETED')
          <form action="{{ route('customer.orders.reorder', $order) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-egreen w-100 rounded-pill py-2">
              <i class="bi bi-arrow-repeat me-2"></i>Reorder These Items
            </button>
          </form>
        @endif
        <a href="{{ route('customer.orders.index') }}" class="btn btn-light border w-100 rounded-pill py-2">
          Back to Orders
        </a>
      </div>
    </div>
  </div>

  {{-- Inline Review Form --}}
  @if($order->status === 'COMPLETED' && !$order->review_submitted)
  <div id="review-section" class="mt-4">
    <div class="card border-0 rounded-4 shadow-sm">
      <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-star-fill text-warning me-2"></i>Leave a Review for this Order</h6>
        <form action="{{ route('customer.reviews.store', $order) }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label fw-semibold small text-dark">Your Rating</label>
            <div class="d-flex gap-2" id="star-rating">
              @for($i = 1; $i <= 5; $i++)
                <label class="fs-4" style="cursor:pointer; color: #e5e7eb; transition: color 0.2s;" data-value="{{ $i }}">
                  <input type="radio" name="rating" value="{{ $i }}" class="d-none" required>
                  <i class="bi bi-star-fill"></i>
                </label>
              @endfor
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold small text-dark">Your Review <span class="text-danger">*</span></label>
            <textarea name="comment" rows="4" class="form-control rounded-3" placeholder="Share your experience with the produce and the farmer..." required minlength="5" maxlength="1000"></textarea>
          </div>
          @if($order->orderItems->count() > 1)
            <div class="mb-3">
              <label class="form-label fw-semibold small text-dark">Reviewing a Specific Product? (Optional)</label>
              <select name="product_id" class="form-select rounded-3">
                <option value="">Overall Order Review</option>
                @foreach($order->orderItems as $item)
                  @if($item->product)
                    <option value="{{ $item->product->id }}">{{ $item->product_name }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          @endif
          <button type="submit" class="btn btn-warning rounded-pill px-4 py-2 fw-bold">
            <i class="bi bi-send me-2"></i>Submit Review
          </button>
        </form>
      </div>
    </div>
  </div>
  @elseif($order->status === 'COMPLETED' && $order->review_submitted)
    <div class="alert alert-success border-0 rounded-4 mt-4">
      <i class="bi bi-check-circle-fill me-2"></i>Thank you! Your review has been submitted for this order.
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const starLabels = document.querySelectorAll('#star-rating label');
  starLabels.forEach((label, idx) => {
    label.addEventListener('click', function () {
      starLabels.forEach((l, i) => {
        l.style.color = i <= idx ? '#f59e0b' : '#e5e7eb';
      });
      label.querySelector('input').checked = true;
    });
    label.addEventListener('mouseover', function () {
      starLabels.forEach((l, i) => {
        l.style.color = i <= idx ? '#f59e0b' : '#e5e7eb';
      });
    });
    label.addEventListener('mouseout', function () {
      const checked = document.querySelector('#star-rating input:checked');
      const checkedIdx = checked ? parseInt(checked.value) - 1 : -1;
      starLabels.forEach((l, i) => {
        l.style.color = i <= checkedIdx ? '#f59e0b' : '#e5e7eb';
      });
    });
  });
});
</script>
@endpush
