@extends('layouts.farmer')

@section('title', 'Stall Profile & Operations — MarketLink')
@section('page-title', 'Stall Profile & Settings')

@push('styles')
<style>
/* ============================================================
   FARMER PROFILE — PREMIUM LUXURY DESIGN SYSTEM
   ============================================================ */

/* Profile Hero Header */
.fp-hero {
  background: linear-gradient(135deg, #071712 0%, #0e2f22 55%, #123c2f 100%);
  border-radius: 20px;
  padding: 2.5rem 2.5rem 4.5rem;
  position: relative;
  overflow: hidden;
  margin-bottom: -3rem;
  box-shadow: 0 20px 60px rgba(7,23,18,0.35);
}

.fp-hero::before {
  content: '';
  position: absolute;
  top: -60%;
  right: -8%;
  width: 420px;
  height: 420px;
  background: radial-gradient(circle, rgba(201,168,106,0.15) 0%, rgba(22,101,52,0.1) 50%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}

.fp-hero::after {
  content: '';
  position: absolute;
  bottom: -20%;
  left: -5%;
  width: 280px;
  height: 280px;
  background: radial-gradient(circle, rgba(168,201,160,0.08) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}

.fp-hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(201,168,106,0.15);
  border: 1px solid rgba(201,168,106,0.3);
  color: #e8c97a;
  font-size: 0.7rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 5px 14px;
  border-radius: 100px;
  margin-bottom: 1rem;
}

.fp-hero-title {
  font-family: var(--font-serif, 'Playfair Display', serif);
  font-size: 1.75rem;
  font-weight: 700;
  color: #FFFFFF;
  margin: 0 0 0.35rem;
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.fp-hero-subtitle {
  color: rgba(255,255,255,0.55);
  font-size: 0.87rem;
  margin: 0;
}

/* Status badge in hero */
.fp-status-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 20px;
  border-radius: 100px;
  font-size: 0.82rem;
  font-weight: 700;
  backdrop-filter: blur(10px);
}
.fp-status-approved {
  background: rgba(34,197,94,0.15);
  border: 1px solid rgba(34,197,94,0.3);
  color: #86efac;
}
.fp-status-pending {
  background: rgba(251,191,36,0.15);
  border: 1px solid rgba(251,191,36,0.3);
  color: #fde68a;
}
.fp-status-suspended {
  background: rgba(239,68,68,0.15);
  border: 1px solid rgba(239,68,68,0.3);
  color: #fca5a5;
}

/* Avatar in hero */
.fp-avatar-wrap {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  border: 3px solid rgba(201,168,106,0.5);
  overflow: hidden;
  background: linear-gradient(135deg, #166534, #15803d);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  box-shadow: 0 8px 24px rgba(0,0,0,0.4);
  flex-shrink: 0;
}
.fp-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }

/* FORM CARD */
.fp-form-card {
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #e8efea;
  box-shadow: 0 8px 40px rgba(18,60,47,0.08);
  position: relative;
  z-index: 2;
  overflow: hidden;
}

/* Section Header */
.fp-section-head {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 1.6rem 2rem 1.2rem;
  border-bottom: 1px solid #f0f5f2;
}

.fp-section-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: linear-gradient(135deg, rgba(20,83,45,0.08), rgba(20,83,45,0.04));
  border: 1px solid rgba(20,83,45,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #15803d;
  font-size: 1rem;
  flex-shrink: 0;
}

.fp-section-title {
  font-weight: 700;
  font-size: 1rem;
  color: #1a2e25;
  margin: 0 0 2px;
  letter-spacing: -0.01em;
}

.fp-section-desc {
  font-size: 0.78rem;
  color: #7c9685;
  margin: 0;
}

/* Section Body */
.fp-section-body {
  padding: 1.6rem 2rem 2rem;
}

.fp-divider {
  height: 8px;
  background: #f7faf8;
  border-top: 1px solid #edf2ef;
  border-bottom: 1px solid #edf2ef;
}

/* Form Controls — Premium Style */
.fp-label {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  color: #2d5a3d;
  margin-bottom: 6px;
  display: block;
}
.fp-label .req { color: #ef4444; }

.fp-input {
  width: 100%;
  background: #f7fdf9;
  border: 1.5px solid #d1e8da;
  border-radius: 10px;
  padding: 11px 15px;
  font-size: 0.9rem;
  color: #1a2e25;
  transition: all 0.2s cubic-bezier(0.16,1,0.3,1);
  outline: none;
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
}
.fp-input:focus {
  border-color: #15803d;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(21,128,61,0.08);
}
.fp-input::placeholder { color: #aec8b6; }
.fp-input.is-invalid {
  border-color: #ef4444;
  background: #fff8f8;
}
.fp-input.is-invalid:focus {
  box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
}

.fp-textarea { resize: vertical; min-height: 110px; }

.fp-hint {
  font-size: 0.73rem;
  color: #94b3a0;
  margin-top: 5px;
}

/* Input Group */
.fp-input-group {
  display: flex;
  border-radius: 10px;
  overflow: hidden;
  border: 1.5px solid #d1e8da;
  transition: all 0.2s;
}
.fp-input-group:focus-within {
  border-color: #15803d;
  box-shadow: 0 0 0 3px rgba(21,128,61,0.08);
}
.fp-input-group .fp-input {
  border: none;
  border-radius: 0;
  flex: 1;
  background: #f7fdf9;
}
.fp-input-group .fp-input:focus {
  box-shadow: none;
  background: #ffffff;
}
.fp-input-group-addon {
  background: #eaf5ef;
  border-left: 1.5px solid #d1e8da;
  padding: 11px 14px;
  font-size: 0.8rem;
  color: #2d5a3d;
  font-weight: 600;
  display: flex;
  align-items: center;
  white-space: nowrap;
}

/* Day Checkboxes */
.fp-day-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.fp-day-chip {
  position: relative;
}
.fp-day-chip input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  width: 0; height: 0;
}
.fp-day-chip label {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 9px 18px;
  border-radius: 100px;
  border: 1.5px solid #d1e8da;
  background: #f7fdf9;
  font-size: 0.84rem;
  font-weight: 600;
  color: #3d6b4f;
  cursor: pointer;
  transition: all 0.2s cubic-bezier(0.16,1,0.3,1);
  user-select: none;
}
.fp-day-chip label:hover {
  border-color: #15803d;
  background: #edf8f2;
}
.fp-day-chip input[type="checkbox"]:checked + label {
  background: linear-gradient(135deg, #166534, #15803d);
  border-color: #15803d;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(21,128,61,0.3);
}
.fp-day-chip label::before {
  content: '';
  width: 14px;
  height: 14px;
  border-radius: 50%;
  border: 2px solid currentColor;
  display: inline-block;
  transition: all 0.2s;
  flex-shrink: 0;
}
.fp-day-chip input[type="checkbox"]:checked + label::before {
  background: rgba(255,255,255,0.5);
  border-color: rgba(255,255,255,0.8);
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3E%3Cpath fill='white' d='M1 4l2 2 4-4'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
}

/* Image Upload Zones */
.fp-upload-zone {
  border: 2px dashed #c8e0d2;
  border-radius: 14px;
  background: #f7fdf9;
  padding: 1.5rem;
  text-align: center;
  transition: all 0.2s;
  cursor: pointer;
  position: relative;
}
.fp-upload-zone:hover {
  border-color: #15803d;
  background: #edf8f2;
}
.fp-upload-zone input[type="file"] {
  position: absolute;
  inset: 0;
  opacity: 0;
  cursor: pointer;
  width: 100%;
  height: 100%;
}
.fp-upload-icon {
  font-size: 2rem;
  color: #7db893;
  margin-bottom: 0.5rem;
  display: block;
}
.fp-upload-label-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: #2d5a3d;
  display: block;
  margin-bottom: 3px;
}
.fp-upload-hint-text {
  font-size: 0.73rem;
  color: #94b3a0;
}

.fp-current-img {
  border-radius: 10px;
  overflow: hidden;
  border: 2px solid #d1e8da;
  margin-bottom: 0.75rem;
}

/* Avatar Preview */
.fp-avatar-preview {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid #d1e8da;
  margin: 0 auto 0.75rem;
}
.fp-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
.fp-avatar-preview-placeholder {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, #166534, #15803d);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  margin: 0 auto 0.75rem;
}

/* Footer Submit */
.fp-footer {
  background: #f7faf8;
  border-top: 1px solid #e8f0eb;
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.fp-footer-note {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  color: #7c9685;
}

.fp-submit-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: linear-gradient(135deg, #166534, #15803d);
  color: #ffffff;
  border: none;
  border-radius: 100px;
  padding: 13px 36px;
  font-size: 0.94rem;
  font-weight: 700;
  font-family: var(--font-body, 'Plus Jakarta Sans', sans-serif);
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.16,1,0.3,1);
  box-shadow: 0 6px 20px rgba(21,128,61,0.35);
  letter-spacing: -0.01em;
}
.fp-submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 28px rgba(21,128,61,0.45);
  background: linear-gradient(135deg, #14532d, #166534);
}
.fp-submit-btn:active {
  transform: translateY(0);
}

/* Invalid feedback */
.fp-error {
  font-size: 0.76rem;
  color: #ef4444;
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 5px;
}
</style>
@endpush

@section('content')

{{-- HERO HEADER --}}
<div class="fp-hero mb-0">
  <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div class="d-flex align-items-center gap-4">
      {{-- Avatar --}}
      <div class="fp-avatar-wrap">
        @if($farmer->profile_image)
          <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="Avatar">
        @else
          🌾
        @endif
      </div>
      <div>
        <div class="fp-hero-badge">
          <i class="bi bi-grid-1x2"></i> Stall Configuration
        </div>
        <h1 class="fp-hero-title">{{ $farmer->stall_name ?? auth()->user()->name }}</h1>
        <p class="fp-hero-subtitle">Manage your farm stall identity, schedule & branding</p>
      </div>
    </div>
    {{-- Status --}}
    <div class="mt-2">
      @if($farmer->isApproved())
        <span class="fp-status-badge fp-status-approved">
          <i class="bi bi-patch-check-fill"></i> Verified & Market-Active
        </span>
      @elseif($farmer->isPending())
        <span class="fp-status-badge fp-status-pending">
          <i class="bi bi-clock-history"></i> Under Review
        </span>
      @else
        <span class="fp-status-badge fp-status-suspended">
          <i class="bi bi-exclamation-triangle-fill"></i> Account Suspended
        </span>
      @endif
    </div>
  </div>
</div>

{{-- FORM CARD --}}
<div class="fp-form-card">
  <form action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ═══════════════════════════════════════════
         SECTION 1: PUBLIC STALL IDENTITY
    ═══════════════════════════════════════════ --}}
    <div class="fp-section-head">
      <div class="fp-section-icon">
        <i class="bi bi-shop"></i>
      </div>
      <div>
        <div class="fp-section-title">Public Stall Information</div>
        <div class="fp-section-desc">This information is visible to customers on the marketplace</div>
      </div>
    </div>

    <div class="fp-section-body">
      <div class="row g-4">

        {{-- Stall Name --}}
        <div class="col-md-6">
          <label for="stall_name" class="fp-label">
            Stall / Farm Display Name <span class="req">*</span>
          </label>
          <input type="text" name="stall_name" id="stall_name"
            class="fp-input @error('stall_name') is-invalid @enderror"
            value="{{ old('stall_name', $farmer->stall_name) }}"
            placeholder="e.g. Highlands Organic Orchard" required>
          @error('stall_name')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Contact Person --}}
        <div class="col-md-6">
          <label for="contact_person" class="fp-label">
            Lead Grower / Representative <span class="req">*</span>
          </label>
          <input type="text" name="contact_person" id="contact_person"
            class="fp-input @error('contact_person') is-invalid @enderror"
            value="{{ old('contact_person', $farmer->contact_person) }}"
            placeholder="Full name of stall manager" required>
          @error('contact_person')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6">
          <label for="email" class="fp-label">
            Administrative Email <span class="req">*</span>
          </label>
          <input type="email" name="email" id="email"
            class="fp-input @error('email') is-invalid @enderror"
            value="{{ old('email', auth()->user()->email) }}"
            placeholder="your@email.com" required>
          @error('email')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Phone --}}
        <div class="col-md-6">
          <label for="phone" class="fp-label">
            Direct Contact Phone <span class="req">*</span>
          </label>
          <input type="text" name="phone" id="phone"
            class="fp-input @error('phone') is-invalid @enderror"
            value="{{ old('phone', $farmer->phone ?? auth()->user()->phone) }}"
            placeholder="+92 300 0000000" required>
          @error('phone')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Address --}}
        <div class="col-12">
          <label for="address" class="fp-label">
            Farm / Homestead Address <span class="req">*</span>
          </label>
          <input type="text" name="address" id="address"
            class="fp-input @error('address') is-invalid @enderror"
            value="{{ old('address', $farmer->address ?? auth()->user()->address) }}"
            placeholder="Full address of your farm estate" required>
          @error('address')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Bio --}}
        <div class="col-12">
          <label for="bio" class="fp-label">Grower Philosophy & Harvest Story</label>
          <textarea name="bio" id="bio" class="fp-input fp-textarea @error('bio') is-invalid @enderror"
            placeholder="Describe your heritage seeds, soil cultivation practices, pesticide-free commitments or harvesting ethics...">{{ old('bio', $farmer->bio) }}</textarea>
          <div class="fp-hint">
            <i class="bi bi-info-circle me-1"></i>
            This story is highlighted on your public stall page to attract conscious patrons.
          </div>
          @error('bio')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

      </div>
    </div>

    {{-- DIVIDER --}}
    <div class="fp-divider"></div>

    {{-- ═══════════════════════════════════════════
         SECTION 2: SCHEDULE & PRE-ORDER RULES
    ═══════════════════════════════════════════ --}}
    <div class="fp-section-head">
      <div class="fp-section-icon">
        <i class="bi bi-clock-history"></i>
      </div>
      <div>
        <div class="fp-section-title">Pre-Order Schedule & Cutoff Rules</div>
        <div class="fp-section-desc">Define your market availability and ordering deadlines</div>
      </div>
    </div>

    <div class="fp-section-body">
      <div class="row g-4">

        {{-- Pickup Windows --}}
        <div class="col-md-6">
          <label for="pickup_windows" class="fp-label">Standard Stall Hours</label>
          <input type="text" name="pickup_windows" id="pickup_windows"
            class="fp-input @error('pickup_windows') is-invalid @enderror"
            value="{{ old('pickup_windows', $farmer->pickup_windows ?? '8:00 AM - 12:00 PM') }}"
            placeholder="e.g. 8:00 AM – 12:00 PM">
          <div class="fp-hint">
            <i class="bi bi-clock me-1"></i>
            Time range when your market booth is staffed for collections.
          </div>
          @error('pickup_windows')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Cutoff Hours --}}
        <div class="col-md-6">
          <label for="order_cutoff_hours" class="fp-label">
            Order Cutoff Window <span class="req">*</span>
          </label>
          <div class="fp-input-group">
            <input type="number" name="order_cutoff_hours" id="order_cutoff_hours"
              class="fp-input @error('order_cutoff_hours') is-invalid @enderror"
              value="{{ old('order_cutoff_hours', $farmer->order_cutoff_hours ?? 24) }}"
              min="1" max="72" required>
            <div class="fp-input-group-addon">
              <i class="bi bi-hourglass-split me-1"></i> Hours Before Market
            </div>
          </div>
          <div class="fp-hint">
            <i class="bi bi-info-circle me-1"></i>
            Guarantees prep and early morning picking time before market kickoff.
          </div>
          @error('order_cutoff_hours')
            <div class="fp-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Operating Days --}}
        <div class="col-12">
          <label class="fp-label d-block mb-3">Active Market Stall Days</label>
          @php
            $currentDays = is_array($farmer->operating_days) ? $farmer->operating_days : [];
            $allDays = ['Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $dayIcons = ['Wednesday'=>'🌿','Thursday'=>'🌾','Friday'=>'🍃','Saturday'=>'🌻','Sunday'=>'☀️'];
          @endphp
          <div class="fp-day-grid">
            @foreach($allDays as $day)
              <div class="fp-day-chip">
                <input type="checkbox" name="operating_days[]"
                  id="day_{{ $day }}" value="{{ $day }}"
                  {{ in_array($day, $currentDays) ? 'checked' : '' }}>
                <label for="day_{{ $day }}">
                  {{ $dayIcons[$day] }} {{ $day }}
                </label>
              </div>
            @endforeach
          </div>
          <div class="fp-hint mt-2">
            <i class="bi bi-calendar3 me-1"></i>
            Select the days your stall will be open at the market venue.
          </div>
        </div>

      </div>
    </div>

    {{-- DIVIDER --}}
    <div class="fp-divider"></div>

    {{-- ═══════════════════════════════════════════
         SECTION 3: IMAGERY & BRANDING
    ═══════════════════════════════════════════ --}}
    <div class="fp-section-head">
      <div class="fp-section-icon">
        <i class="bi bi-images"></i>
      </div>
      <div>
        <div class="fp-section-title">Visual Presentation & Stall Branding</div>
        <div class="fp-section-desc">Upload your stall logo and banner image for marketplace visibility</div>
      </div>
    </div>

    <div class="fp-section-body">
      <div class="row g-4">

        {{-- Profile Photo --}}
        <div class="col-md-6">
          <label class="fp-label mb-3 d-block">Stall Logo / Grower Portrait</label>

          {{-- Current avatar --}}
          @if($farmer->profile_image)
            <div class="fp-avatar-preview mx-auto">
              <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="Avatar">
            </div>
          @else
            <div class="fp-avatar-preview-placeholder mx-auto">🌾</div>
          @endif

          <div class="fp-upload-zone">
            <input type="file" name="profile_image" id="profile_image" accept="image/*">
            <i class="bi bi-camera fp-upload-icon"></i>
            <span class="fp-upload-label-text">Click to upload portrait</span>
            <span class="fp-upload-hint-text">Square ratio recommended • Max 2MB</span>
          </div>
          @error('profile_image')
            <div class="fp-error mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

        {{-- Banner Image --}}
        <div class="col-md-6">
          <label class="fp-label mb-3 d-block">Market Stall Banner</label>

          {{-- Current banner --}}
          @if($farmer->banner_image)
            <div class="fp-current-img" style="height: 80px;">
              <img src="{{ str_starts_with($farmer->banner_image, 'http') ? $farmer->banner_image : asset('storage/' . $farmer->banner_image) }}"
                alt="Banner" style="width:100%;height:100%;object-fit:cover;">
            </div>
          @endif

          <div class="fp-upload-zone">
            <input type="file" name="banner_image" id="banner_image" accept="image/*">
            <i class="bi bi-panorama fp-upload-icon"></i>
            <span class="fp-upload-label-text">Click to upload banner</span>
            <span class="fp-upload-hint-text">Landscape format of farm or produce • Max 3MB</span>
          </div>
          @error('banner_image')
            <div class="fp-error mt-2"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
          @enderror
        </div>

      </div>
    </div>

    {{-- FOOTER / SUBMIT --}}
    <div class="fp-footer">
      <div class="fp-footer-note">
        <i class="bi bi-lightning-charge-fill text-success"></i>
        Changes are immediately reflected on the patron marketplace.
      </div>
      <button type="submit" class="fp-submit-btn">
        <i class="bi bi-check2-circle"></i>
        Save Stall Profile
      </button>
    </div>

  </form>
</div>

@endsection
