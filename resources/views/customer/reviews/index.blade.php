@extends('layouts.customer')

@section('title', 'My Reviews')
@section('page-title', 'My Reviews')

@section('content')

{{-- Stats banner --}}
<div class="row g-3 mb-5">
  <div class="col-6 col-md-3">
    <div class="stat-card-lux">
      <div class="stat-card-icon-wrap" style="background: #FFF8E7;">
        <i class="bi bi-star-fill" style="color: #d97706; font-size: 1.2rem;"></i>
      </div>
      <div class="stat-card-num">{{ $totalReviews }}</div>
      <div class="stat-card-label">Reviews Written</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card-lux">
      <div class="stat-card-icon-wrap" style="background: var(--color-sage-soft);">
        <i class="bi bi-award" style="color: var(--color-secondary); font-size: 1.2rem;"></i>
      </div>
      <div class="stat-card-num">{{ number_format($avgRating, 1) }}</div>
      <div class="stat-card-label">Avg Rating Given</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card-lux">
      <div class="stat-card-icon-wrap" style="background: var(--color-info-soft);">
        <i class="bi bi-bag-check" style="color: var(--color-info); font-size: 1.2rem;"></i>
      </div>
      <div class="stat-card-num">{{ $reviewableOrders }}</div>
      <div class="stat-card-label">Pending Reviews</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="stat-card-lux">
      <div class="stat-card-icon-wrap" style="background: var(--color-success-soft);">
        <i class="bi bi-people" style="color: var(--color-success); font-size: 1.2rem;"></i>
      </div>
      <div class="stat-card-num">{{ $farmersReviewed }}</div>
      <div class="stat-card-label">Farmers Reviewed</div>
    </div>
  </div>
</div>

{{-- Orders ready for review --}}
@if($pendingReviewOrders->count() > 0)
<div class="mb-5">
  <div class="portal-section-head">
    <h2 class="portal-section-title"><i class="bi bi-pencil-square me-2" style="color: var(--color-accent);"></i>Ready to Review</h2>
    <span style="font-size: 0.8rem; color: var(--color-text-muted);">{{ $pendingReviewOrders->count() }} completed order(s) awaiting your feedback</span>
  </div>
  <div class="row g-3 mt-1">
    @foreach($pendingReviewOrders as $order)
      <div class="col-lg-6">
        <div class="portal-card h-100" style="border-left: 3px solid var(--color-accent);">
          <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div>
              <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">#{{ $order->order_number }}</div>
              <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 0.2rem;">
                <i class="bi bi-shop me-1"></i>{{ $order->farmer->stall_name ?? '—' }}
                @if($order->pickup_date)
                  · <i class="bi bi-calendar3 me-1"></i>{{ $order->pickup_date->format('d M Y') }}
                @endif
              </div>
            </div>
            <span class="badge rounded-pill" style="background: var(--color-success-soft); color: var(--color-success); font-size: 0.72rem; font-weight: 700; padding: 0.35rem 0.75rem;">Completed</span>
          </div>
          {{-- Items --}}
          <div class="d-flex flex-wrap gap-2 mb-3">
            @foreach($order->orderItems->take(3) as $item)
              <span style="font-size: 0.76rem; background: var(--color-bg); border: 1px solid var(--color-border); border-radius: 20px; padding: 0.25rem 0.65rem; color: var(--color-text-dark);">
                {{ $item->product_name }} ×{{ $item->quantity }}
              </span>
            @endforeach
            @if($order->orderItems->count() > 3)
              <span style="font-size: 0.76rem; color: var(--color-text-muted);">+{{ $order->orderItems->count() - 3 }} more</span>
            @endif
          </div>
          <a href="{{ route('customer.orders.show', $order) }}#review-section"
             class="btn btn-sm rounded-pill px-4"
             style="background: var(--color-accent); color: #1a1a1a; font-weight: 700; font-size: 0.8rem; border: none;">
            <i class="bi bi-star me-1"></i>Write Review
          </a>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

{{-- My Reviews List --}}
<div class="portal-section-head">
  <h2 class="portal-section-title"><i class="bi bi-chat-quote me-2" style="color: var(--color-secondary);"></i>My Reviews ({{ $totalReviews }})</h2>
</div>

@forelse($reviews as $review)
  <div class="portal-card mb-4">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
      {{-- Left: product & farmer --}}
      <div class="d-flex gap-3 align-items-start flex-grow-1">
        {{-- Image --}}
        <div style="width: 56px; height: 56px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: var(--color-sage-soft); border: 1px solid var(--color-border);">
          @if($review->product && $review->product->image)
            <img src="{{ str_starts_with($review->product->image, 'http') ? $review->product->image : asset('storage/' . $review->product->image) }}"
                 alt="{{ $review->product->name }}"
                 style="width: 100%; height: 100%; object-fit: cover;">
          @else
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🥦</div>
          @endif
        </div>
        {{-- Info --}}
        <div class="flex-grow-1">
          <div style="font-weight: 700; color: var(--color-dark); font-size: 0.92rem; margin-bottom: 0.2rem;">
            {{ $review->product->name ?? 'General Order Review' }}
          </div>
          @if($review->farmer)
            <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-bottom: 0.5rem;">
              <i class="bi bi-shop me-1"></i>{{ $review->farmer->stall_name }}
            </div>
          @endif
          {{-- Stars --}}
          <div class="d-flex align-items-center gap-2 mb-2">
            <div class="d-flex gap-1">
              @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" style="color: {{ $i <= $review->rating ? '#f59e0b' : '#e5e7eb' }}; font-size: 0.9rem;"></i>
              @endfor
            </div>
            <span style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $review->created_at->format('d M Y') }}</span>
            @if($review->status === 'approved')
              <span style="font-size: 0.7rem; background: var(--color-success-soft); color: var(--color-success); border-radius: 20px; padding: 0.15rem 0.5rem; font-weight: 700;">✓ Published</span>
            @elseif($review->status === 'pending')
              <span style="font-size: 0.7rem; background: var(--color-warning-soft); color: #7a5c1e; border-radius: 20px; padding: 0.15rem 0.5rem; font-weight: 700;">⏳ Under Review</span>
            @endif
          </div>
          {{-- Comment --}}
          @if($review->comment)
            <p style="font-size: 0.85rem; color: var(--color-text); line-height: 1.6; margin: 0;">
              "{{ $review->comment }}"
            </p>
          @endif
          {{-- Farmer Response --}}
          @if($review->farmer_response)
            <div class="mt-3 p-3 rounded-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4);">
              <div style="font-size: 0.74rem; font-weight: 700; color: var(--color-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                <i class="bi bi-reply me-1"></i>Farmer's Response
              </div>
              <p style="font-size: 0.83rem; color: var(--color-text); margin: 0; line-height: 1.5;">
                "{{ $review->farmer_response }}"
              </p>
            </div>
          @endif
        </div>
      </div>
      {{-- Right: view links --}}
      <div class="d-flex flex-column gap-2" style="flex-shrink: 0;">
        @if($review->product)
          <a href="{{ route('products.show', $review->product) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-size: 0.78rem; font-weight: 600;">
            View Product
          </a>
        @endif
        @if($review->farmer)
          <a href="{{ route('farmers.show', $review->farmer) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.78rem; font-weight: 600;">
            View Stall
          </a>
        @endif
      </div>
    </div>
  </div>
@empty
  <div class="portal-card text-center" style="padding: 5rem 2rem;">
    <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1rem;">⭐</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">No Reviews Yet</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 380px; margin: 0 auto 1.5rem;">
      Complete a pickup order and share your experience to help other shoppers find the best local produce.
    </p>
    <a href="{{ route('products.index') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.75rem; font-size: 0.88rem;">
      <i class="bi bi-basket2"></i> Browse Produce
    </a>
  </div>
@endforelse

@if($reviews->hasPages())
  <div class="mt-4">{{ $reviews->links('pagination::bootstrap-5') }}</div>
@endif

@endsection
