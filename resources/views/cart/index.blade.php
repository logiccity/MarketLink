@extends('layouts.app')

@section('title', 'Your Harvest Basket — MarketLink')

@section('content')
<div class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(20, 83, 45, 0.04) 0%, rgba(248, 250, 252, 0.9) 90%); min-height: 80vh;">
  <div class="container" style="max-width: 1200px;">

    {{-- Breadcrumb / Back --}}
    <div class="mb-4">
      <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 hover-text-primary">
        <i class="bi bi-arrow-left"></i>
        <span>Explore Fresh Produce</span>
      </a>
    </div>

    {{-- Page Header --}}
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-5">
      <div>
        <div class="badge rounded-pill px-3 py-2 text-uppercase mb-2" style="background: rgba(20, 83, 45, 0.08); color: #14532d; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
          Direct From Local Growers
        </div>
        <h1 class="fw-bold text-dark mb-1" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.02em; font-size: 2.2rem;">
          Your Harvest Basket
        </h1>
        <p class="text-muted mb-0" style="font-size: 0.95rem;">
          {{ count($cart) }} item(s) selected for market stall reservation
        </p>
      </div>

      @if(count($cart) > 0)
        <div>
          <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.82rem;" onclick="return confirm('Are you sure you wish to empty your basket?')">
              <i class="bi bi-trash3"></i>
              <span>Empty Basket</span>
            </button>
          </form>
        </div>
      @endif
    </div>

    @if(session('success'))
      <div class="alert border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3 p-3" style="background: rgba(20, 83, 45, 0.08); border-left: 4px solid #15803d !important; color: #14532d;">
        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
        <div class="fw-medium small">{{ session('success') }}</div>
      </div>
    @endif

    @if(session('error'))
      <div class="alert border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3 p-3" style="background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626 !important; color: #991b1b;">
        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
        <div class="fw-medium small">{{ session('error') }}</div>
      </div>
    @endif

    @if(!empty($validationErrors))
      <div class="alert border-0 rounded-4 shadow-sm mb-4 p-4" style="background: rgba(217, 119, 6, 0.08); border-left: 4px solid #d97706 !important; color: #92400e;">
        <div class="d-flex align-items-center gap-2 fw-bold mb-2">
          <i class="bi bi-exclamation-circle-fill text-warning"></i>
          <span>Basket Availability Updates</span>
        </div>
        <ul class="mb-0 ps-3 small">
          @foreach($validationErrors as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(count($cart) > 0)
      <div class="row g-5">
        {{-- Left: Item Listings --}}
        <div class="col-lg-8">
          <div class="d-flex flex-column gap-3">
            @foreach($cart as $productId => $item)
              <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.04); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-3 p-md-4">
                  <div class="row align-items-center g-3 cart-item-row">
                    {{-- Thumbnail --}}
                    <div class="col-auto cart-col-thumb">
                      <div class="rounded-4 overflow-hidden position-relative shadow-sm" style="width: 88px; height: 88px; background: #f8fafc;">
                        @if($item['image'] ?? null)
                          <img src="{{ str_starts_with($item['image'], 'http') || str_starts_with($item['image'], '/') ? $item['image'] : asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100" style="object-fit: cover;">
                        @else
                          <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); font-size: 2rem;">
                            🌾
                          </div>
                        @endif
                      </div>
                    </div>

                    {{-- Product Info --}}
                    <div class="col cart-col-info">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge rounded-pill px-2 py-1" style="background: rgba(20,83,45,0.06); color: #166534; font-size: 0.7rem; font-weight: 600;">
                          {{ $item['farmer_name'] ?? 'Local Producer' }}
                        </span>
                      </div>
                      <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">
                        {{ $item['name'] }}
                      </h5>
                      <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="color: #15803d; font-size: 0.95rem;">
                          PKR {{ number_format($item['price'], 2) }}
                        </span>
                        <span class="text-muted" style="font-size: 0.8rem;">/ {{ $item['unit'] ?? 'unit' }}</span>
                      </div>
                      @if($item['cutoff'] ?? null)
                        <div class="d-inline-flex align-items-center gap-1 mt-2 px-2 py-1 rounded-3" style="background: rgba(217, 119, 6, 0.08); color: #b45309; font-size: 0.72rem; font-weight: 600;">
                          <i class="bi bi-clock"></i>
                          <span>Order by {{ \Carbon\Carbon::parse($item['cutoff'])->format('D, M j - g:i A') }}</span>
                        </div>
                      @endif
                    </div>

                    {{-- Quantity Selector --}}
                    <div class="col-auto cart-col-qty">
                      <div class="d-flex align-items-center rounded-pill px-1 py-1" style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline m-0">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="product_id" value="{{ $productId }}">
                          <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                          <button type="submit" class="btn btn-sm btn-link text-dark p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; text-decoration: none; border-radius: 50%;">
                            <i class="bi bi-dash" style="font-size: 1rem;"></i>
                          </button>
                        </form>
                        <span class="px-3 fw-bold text-dark" style="font-size: 0.92rem; min-width: 32px; text-align: center;">
                          {{ $item['quantity'] }}
                        </span>
                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline m-0">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="product_id" value="{{ $productId }}">
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                          <button type="submit" class="btn btn-sm btn-link text-dark p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; text-decoration: none; border-radius: 50%;">
                            <i class="bi bi-plus" style="font-size: 1rem;"></i>
                          </button>
                        </form>
                      </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="col-auto text-end cart-col-total" style="min-width: 100px;">
                      <div class="text-muted small" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Total</div>
                      <div class="fw-bold text-dark" style="font-size: 1.1rem;">
                        PKR {{ number_format($item['price'] * $item['quantity'], 2) }}
                      </div>
                    </div>

                    {{-- Remove Button --}}
                    <div class="col-auto cart-col-remove">
                      <form action="{{ route('cart.remove', $productId) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm p-2 text-muted hover-danger" style="border: none; background: transparent; transition: color 0.15s ease;" title="Remove this item">
                          <i class="bi bi-x-circle fs-5"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Continue Browsing --}}
          <div class="mt-4 pt-2">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2" style="font-size: 0.88rem; font-weight: 600;">
              <i class="bi bi-plus-circle"></i>
              <span>Add More Produce</span>
            </a>
          </div>
        </div>

        {{-- Right: Checkout Summary Card --}}
        <div class="col-lg-4">
          <div class="sticky-top" style="top: 100px;">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.06);">
              {{-- Card Header --}}
              <div class="p-4" style="background: linear-gradient(135deg, #14532d 0%, #064e3b 100%); color: #ffffff;">
                <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(255,255,255,0.15); font-size: 0.68rem; letter-spacing: 0.08em; font-weight: 700;">
                  Reservation Overview
                </span>
                <h4 class="fw-bold mb-0" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em;">
                  Order Summary
                </h4>
              </div>

              {{-- Card Body --}}
              <div class="card-body p-4">
                <div class="d-flex flex-column gap-3 mb-4">
                  @foreach($cart as $item)
                    <div class="d-flex justify-content-between align-items-center small">
                      <div class="text-truncate pe-2">
                        <span class="fw-semibold text-dark">{{ $item['name'] }}</span>
                        <span class="text-muted"> × {{ $item['quantity'] }}</span>
                      </div>
                      <span class="fw-bold text-dark flex-shrink-0">PKR {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                  @endforeach
                </div>

                <div class="p-3 rounded-3 mb-4" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Estimated Items</span>
                    <span class="fw-semibold small text-dark">{{ count($cart) }} products</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Platform Reservation</span>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 0.72rem; font-weight: 700;">Complimentary</span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Payment Method</span>
                    <span class="small fw-semibold text-dark">Cash at Market Stall</span>
                  </div>
                </div>

                <div class="pt-3 border-top mb-4">
                  <div class="d-flex justify-content-between align-items-baseline">
                    <span class="fw-bold text-dark" style="font-size: 1rem;">Total Due at Pickup</span>
                    <span class="fw-bold" style="color: #15803d; font-size: 1.6rem; letter-spacing: -0.02em;">
                      PKR {{ number_format($subtotal ?? 0, 2) }}
                    </span>
                  </div>
                  <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                    Pay directly to each grower upon receiving your freshly harvested goods.
                  </div>
                </div>

                @guest
                  <a href="{{ route('login') }}" class="btn w-100 rounded-pill py-3 fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.95rem;">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Sign In to Reserve</span>
                  </a>
                @else
                  <a href="{{ route('checkout.index') }}" class="btn w-100 rounded-pill py-3 fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.98rem; letter-spacing: 0.01em;">
                    <span>Proceed to Confirm Pre-Order</span>
                    <i class="bi bi-arrow-right"></i>
                  </a>
                @endguest

                {{-- Guarantee notice --}}
                <div class="mt-4 p-3 rounded-4" style="background: rgba(20, 83, 45, 0.04); border: 1px solid rgba(20, 83, 45, 0.08);">
                  <div class="d-flex gap-3 align-items-start">
                    <i class="bi bi-shield-check fs-5" style="color: #15803d; flex-shrink: 0; margin-top: -2px;"></i>
                    <div style="font-size: 0.78rem; color: #1e3a1e; line-height: 1.45;">
                      <strong>Zero Risk Pre-Orders</strong>: No credit card charged. Farmers reserve harvest lots specifically for your selected pickup window.
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      {{-- Empty Basket State --}}
      <div class="card border-0 rounded-5 shadow-sm text-center py-5 px-4 my-4" style="background: #ffffff; max-width: 620px; margin: 0 auto;">
        <div class="py-4">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 100px; height: 100px; background: rgba(20, 83, 45, 0.06); color: #15803d; font-size: 2.8rem;">
            🛒
          </div>
          <h3 class="fw-bold text-dark mb-2" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em;">
            Your Harvest Basket is Empty
          </h3>
          <p class="text-muted mb-4 mx-auto" style="max-width: 440px; font-size: 0.95rem; line-height: 1.6;">
            Explore directly sourced artisanal fruits, organic greens, and handcrafted farm specialties ready for market pickup.
          </p>
          <a href="{{ route('products.index') }}" class="btn rounded-pill px-5 py-3 fw-bold text-white shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.95rem;">
            <i class="bi bi-basket2"></i>
            <span>Browse Fresh Harvests</span>
          </a>
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
