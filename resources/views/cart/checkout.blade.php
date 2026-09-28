@extends('layouts.app')

@section('title', 'Checkout — Place Your Pre-Order')

@section('content')
<div class="container py-5" style="max-width: 1000px;">
  <div class="row align-items-center mb-5">
    <div class="col">
      <h1 class="fw-bold text-dark" style="font-family: var(--font-heading);">Confirm Your Pre-Order</h1>
      <p class="text-muted">Review your order details and confirm your pickup reservation. Payment is made in cash at the farmer's stall.</p>
    </div>
  </div>

  <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="row g-5">
      <!-- Left: Order Details -->
      <div class="col-lg-7">
        <!-- Cart Items Review -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
          <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark"><i class="bi bi-basket2 text-success me-2"></i>Your Items</h5>
          </div>
          <div class="card-body px-4 pb-4">
            @foreach($cart as $productId => $item)
              <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 60px; height: 60px;">
                  @if($item['image'] ?? null)
                    <img src="{{ str_starts_with($item['image'], 'http') || str_starts_with($item['image'], '/') ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100" style="object-fit: cover;">
                  @else
                    <div class="w-100 h-100 bg-success-subtle d-flex align-items-center justify-content-center" style="font-size: 1.5rem;">🌿</div>
                  @endif
                </div>
                <div class="flex-grow-1">
                  <div class="fw-semibold small text-dark">{{ $item['name'] }}</div>
                  <div class="text-muted" style="font-size: 0.78rem;">by {{ $item['farmer_name'] ?? 'Farmer' }} &bull; {{ $item['quantity'] }} × PKR {{ number_format($item['price'], 2) }}</div>
                  @if($item['pickup_slot_label'] ?? null)
                    <div class="small text-success mt-1"><i class="bi bi-clock me-1"></i>{{ $item['pickup_slot_label'] }}</div>
                  @endif
                </div>
                <div class="fw-bold text-dark">PKR {{ number_format($item['price'] * $item['quantity'], 2) }}</div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Contact Details -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
          <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark"><i class="bi bi-person-circle text-success me-2"></i>Your Details</h5>
          </div>
          <div class="card-body px-4 pb-4">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Full Name</label>
                <input type="text" name="name" class="form-control rounded-3" value="{{ auth()->user()->name }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small text-dark">Phone Number</label>
                <input type="text" name="phone" class="form-control rounded-3" value="{{ auth()->user()->phone }}" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small text-dark">Email Address</label>
                <input type="email" name="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small text-dark">Special Instructions for Farmers (optional)</label>
                <textarea name="customer_notes" rows="2" class="form-control rounded-3" placeholder="Any special requests, e.g. ripeness preference, alternative product...">{{ old('customer_notes') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Payment Notice -->
        <div class="alert border-0 rounded-4 p-4" style="background: var(--gold-light);">
          <div class="d-flex gap-3">
            <div style="font-size: 2rem; flex-shrink: 0;">💵</div>
            <div>
              <h6 class="fw-bold mb-1" style="color: #7a5c1e;">Cash Payment at Pickup</h6>
              <p class="small mb-0" style="color: #8a6820;">
                MarketLink is a pre-order reservation platform. There is <strong>no online payment</strong>. You will pay the farmer directly in cash when you collect your order at the market stall on the confirmed pickup date.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: Order Summary -->
      <div class="col-lg-5">
        <div class="sticky-top" style="top: 80px;">
          <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
              <h5 class="fw-bold text-dark">Order Summary</h5>
            </div>
            <div class="card-body px-4 pb-2">
              @foreach($groupedByFarmer as $farmerName => $farmerItems)
                <div class="mb-3">
                  <div class="fw-semibold small text-dark mb-2 pb-1 border-bottom">🌾 {{ $farmerName }}</div>
                  @foreach($farmerItems as $item)
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                      <span class="text-muted">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                      <span class="fw-semibold">PKR {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                    @if($item['pickup_slot_label'] ?? null)
                      <div class="text-success small mb-2" style="font-size: 0.72rem;"><i class="bi bi-clock me-1"></i>{{ $item['pickup_slot_label'] }}</div>
                    @endif
                  @endforeach
                </div>
              @endforeach
            </div>
            <div class="card-footer bg-white border-0 px-4 pb-4">
              <hr>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small text-muted">Platform Fee</span>
                <span class="small text-success">FREE</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="small text-muted">Delivery</span>
                <span class="small text-success">N/A — Pickup Only</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fw-bold text-dark">Total at Pickup</span>
                <span class="fw-bold text-success fs-4">PKR {{ number_format($total, 2) }}</span>
              </div>

              <button type="submit" class="btn btn-egreen w-100 rounded-pill py-3 fw-bold" id="placeOrderBtn">
                <i class="bi bi-check-circle-fill me-2"></i>Place Pre-Order Reservation
              </button>

              <p class="text-muted small text-center mt-3 mb-0">
                <i class="bi bi-shield-check text-success me-1"></i>
                Your order is a reservation only. The farmer will confirm within 24 hours.
              </p>

              <div class="mt-3 text-center">
                <a href="{{ route('cart.index') }}" class="text-muted small text-decoration-none">
                  <i class="bi bi-arrow-left me-1"></i>Return to Basket
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('checkoutForm')?.addEventListener('submit', function() {
  const btn = document.getElementById('placeOrderBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Placing your order...';
});
</script>
@endpush
