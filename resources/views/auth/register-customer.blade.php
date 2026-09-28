@extends('layouts.auth')

@section('title', 'Join as a Shopper')

@section('content')
  <div class="mb-2">
    <a href="{{ route('register') }}" style="color:rgba(255,255,255,0.42); font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.72)'" onmouseout="this.style.color='rgba(255,255,255,0.42)'">
      <i class="bi bi-arrow-left"></i> Back to account type
    </a>
  </div>
  <div class="mb-4">
    <div style="display:inline-flex; align-items:center; gap:0.45rem; background:rgba(22,132,91,0.12); border:1px solid rgba(22,132,91,0.3); color:#4ade80; font-size:0.7rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; padding:0.28rem 0.8rem; border-radius:50px; margin-bottom:1rem;"><i class="bi bi-record-circle"></i> Step 2 of 2 — Customer</div>
    <span class="badge-organic" style="background: var(--color-sage-soft); color: var(--color-primary); border-color: var(--color-sage);">
      🧺 Shopper Registration
    </span>
    <h1 class="h2 mt-2 mb-1" style="font-family: var(--font-serif); color: var(--color-dark);">Join the Community</h1>
    <p class="text-muted-lux small mb-0">Reserve fresh seasonal produce from verified local family growers.</p>
  </div>

  <form method="POST" action="{{ route('register.customer.submit') }}">

    @csrf
    <div class="row g-3">
      <div class="col-12">
        <label for="name" class="form-label-lux">Full Name</label>
        <input type="text" name="name" id="name" class="form-control form-control-lux w-100" value="{{ old('name') }}" placeholder="e.g. Eleanor Vance" required autofocus>
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label-lux">Email Address</label>
        <input type="email" name="email" id="email" class="form-control form-control-lux w-100" value="{{ old('email') }}" placeholder="name@domain.com" required autocomplete="email">
      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label-lux">Contact Telephone</label>
        <input type="text" name="phone" id="phone" class="form-control form-control-lux w-100" value="{{ old('phone') }}" placeholder="+27 (0) 71 234 5678" required>
      </div>

      <div class="col-12">
        <label for="address" class="form-label-lux">Residential Area / Neighbourhood</label>
        <input type="text" name="address" id="address" class="form-control form-control-lux w-100" value="{{ old('address') }}" placeholder="e.g. Parkview, Rosebank, Craighall" required>
      </div>

      <div class="col-md-6">
        <label for="password" class="form-label-lux">Create Password</label>
        <input type="password" name="password" id="password" class="form-control form-control-lux w-100" placeholder="Minimum 8 characters" required autocomplete="new-password">
      </div>

      <div class="col-md-6">
        <label for="password_confirmation" class="form-label-lux">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lux w-100" placeholder="Repeat your password" required>
      </div>

      <div class="col-12">
        <div class="p-3 rounded-3 small" style="background: var(--color-sage-soft); border: 1px solid var(--color-sage); color: var(--color-primary);">
          <div class="d-flex align-items-center gap-2 fw-bold mb-1">
            <i class="bi bi-shield-check fs-5" style="color: var(--color-primary);"></i>
            <span>The Zero-Payment Reservation Guarantee</span>
          </div>
          <div style="font-size: 0.78rem; line-height: 1.5;">
            No card or bank details are required. MarketLink is a harvest pre-order platform. You pay cash directly to the grower at their stall upon pickup.
          </div>
        </div>
      </div>

      <div class="col-12 mt-4">
        <button type="submit" class="btn btn-lux-primary w-100 py-3">
          <i class="bi bi-check2-circle"></i>
          <span>Create Shopper Account</span>
        </button>
      </div>
    </div>
  </form>

  <div class="mt-4 pt-3 border-top text-center text-muted small" style="border-color: var(--color-border-subtle) !important;">
    <span>Already have an eGreen Basket account?</span>
    <a href="{{ route('login') }}" class="text-forest fw-semibold ms-1 text-decoration-none">Sign In</a>
    <span class="mx-2">•</span>
    <a href="{{ route('register.farmer') }}" class="text-forest fw-semibold text-decoration-none">Grower Stall Registration</a>
  </div>
@endsection
