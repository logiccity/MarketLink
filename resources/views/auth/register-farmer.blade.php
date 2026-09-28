@extends('layouts.auth')

@section('title', 'Grower Stall Registration')

@section('content')
  <div class="mb-2">
    <a href="{{ route('register') }}" style="color:rgba(255,255,255,0.42); font-size:0.82rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; transition:color 0.2s;" onmouseover="this.style.color='rgba(255,255,255,0.72)'" onmouseout="this.style.color='rgba(255,255,255,0.42)'">
      <i class="bi bi-arrow-left"></i> Back to account type
    </a>
  </div>
  <div class="mb-4">
    <div style="display:inline-flex; align-items:center; gap:0.45rem; background:rgba(201,168,106,0.1); border:1px solid rgba(201,168,106,0.3); color:#D4B477; font-size:0.7rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; padding:0.28rem 0.8rem; border-radius:50px; margin-bottom:1rem;"><i class="bi bi-record-circle"></i> Step 2 of 2 — Farmer</div>
    <span class="badge-organic" style="background: var(--color-accent-subtle); color: var(--color-dark); border-color: var(--color-accent);">
      🌾 Farm Stall Registration
    </span>
    <h1 class="h2 mt-2 mb-1" style="font-family: var(--font-serif); color: var(--color-dark);">List Your Produce</h1>
    <p class="text-muted-lux small mb-0">Register your farm stall to connect with conscious shoppers and receive harvest pre-orders.</p>
  </div>

  <form method="POST" action="{{ route('register.farmer.submit') }}">
    @csrf
    <div class="row g-3">
      <div class="col-md-6">
        <label for="stall_name" class="form-label-lux">Stall / Farm Name</label>
        <input type="text" name="stall_name" id="stall_name" class="form-control form-control-lux w-100" value="{{ old('stall_name') }}" placeholder="e.g. Cedar Peak Organic Farm" required autofocus>
      </div>

      <div class="col-md-6">
        <label for="contact_person" class="form-label-lux">Primary Contact Person</label>
        <input type="text" name="contact_person" id="contact_person" class="form-control form-control-lux w-100" value="{{ old('contact_person') }}" placeholder="e.g. Thomas Thorne" required>
      </div>

      <div class="col-md-6">
        <label for="email" class="form-label-lux">Account Email</label>
        <input type="email" name="email" id="email" class="form-control form-control-lux w-100" value="{{ old('email') }}" placeholder="grower@domain.com" required>
      </div>

      <div class="col-md-6">
        <label for="phone" class="form-label-lux">Phone Number</label>
        <input type="text" name="phone" id="phone" class="form-control form-control-lux w-100" value="{{ old('phone') }}" placeholder="+27 (0) 82 555 4321" required>
      </div>

      <div class="col-12">
        <label for="address" class="form-label-lux">Farm Location / Primary Growing Address</label>
        <input type="text" name="address" id="address" class="form-control form-control-lux w-100" value="{{ old('address') }}" placeholder="e.g. Plot 14, Skeerpoort Valley" required>
      </div>

      <div class="col-12">
        <label for="bio" class="form-label-lux">Farm Ethos & Specialities (optional)</label>
        <textarea name="bio" id="bio" rows="2" class="form-control form-control-lux w-100" placeholder="Describe your organic growing techniques, heritage seed lines, or seasonal specialities...">{{ old('bio') }}</textarea>
      </div>

      <div class="col-md-6">
        <label for="pickup_windows" class="form-label-lux">Standard Stall Hours</label>
        <input type="text" name="pickup_windows" id="pickup_windows" class="form-control form-control-lux w-100" value="{{ old('pickup_windows', '08:00 - 13:00') }}" placeholder="e.g. 08:00 - 13:00">
      </div>

      <div class="col-md-6">
        <label class="form-label-lux">Usual Market Days</label>
        <div class="d-flex flex-wrap gap-2 mt-1">
          @foreach(['Saturday', 'Sunday', 'Wednesday', 'Friday'] as $d)
            <label class="form-check-label small" style="cursor: pointer;">
              <input class="form-check-input me-1" type="checkbox" name="operating_days[]" value="{{ $d }}" id="day_{{ $d }}" {{ in_array($d, old('operating_days', ['Saturday', 'Sunday'])) ? 'checked' : '' }}>
              <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">{{ $d }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="col-md-6">
        <label for="password" class="form-label-lux">Account Password</label>
        <input type="password" name="password" id="password" class="form-control form-control-lux w-100" placeholder="Minimum 8 characters" required autocomplete="new-password">
      </div>

      <div class="col-md-6">
        <label for="password_confirmation" class="form-label-lux">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lux w-100" placeholder="Repeat password" required>
      </div>

      <div class="col-12">
        <div class="p-3 rounded-3 small" style="background: var(--color-accent-subtle); border: 1px solid var(--color-accent-light); color: var(--color-dark);">
          <div class="d-flex align-items-center gap-2 fw-bold mb-1 text-gold">
            <i class="bi bi-hourglass-split fs-5"></i>
            <span>Verified Grower Review Protocol</span>
          </div>
          <div style="font-size: 0.78rem; line-height: 1.5;">
            Following registration, our community curation team reviews stall legitimacy before activating your listing. Once approved, you gain full access to weekly stock forecasting, pickup slots, and incoming customer reservations.
          </div>
        </div>
      </div>

      <div class="col-12 mt-4">
        <button type="submit" class="btn btn-lux-gold w-100 py-3">
          <i class="bi bi-shop"></i>
          <span>Submit Grower Stall Application</span>
        </button>
      </div>
    </div>
  </form>

  <div class="mt-4 pt-3 border-top text-center text-muted small" style="border-color: var(--color-border-subtle) !important;">
    <span>Already registered?</span>
    <a href="{{ route('login', ['role' => 'farmer']) }}" class="text-forest fw-semibold ms-1 text-decoration-none">Grower Sign In</a>
  </div>
@endsection
