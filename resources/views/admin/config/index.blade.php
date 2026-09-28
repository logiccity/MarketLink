@extends('layouts.admin')

@section('title', 'Platform Configuration & System Settings')
@section('page-title', 'Platform Settings & Configuration')

@section('content')

{{-- Top Header Banner --}}
<div class="portal-card mb-4" style="background: linear-gradient(135deg, rgba(20, 83, 45, 0.08) 0%, rgba(212, 180, 119, 0.08) 100%); border-color: rgba(20, 83, 45, 0.15) !important;">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase fw-bold" style="background: rgba(20, 83, 45, 0.12); color: #14532d; font-size: 0.72rem; letter-spacing: 0.08em;">
        <i class="bi bi-gear-fill me-1"></i> Core Infrastructure
      </span>
      <h3 class="fw-bold text-dark mb-1" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif);">
        System Parameters & Global Rules
      </h3>
      <p class="text-muted small mb-0">
        Control marketplace operating deadlines, customer reservation cutoff policies, grower review gates, and system caches.
      </p>
    </div>

    {{-- Cache Maintenance Dropdown --}}
    <div class="dropdown">
      <button class="btn btn-dark rounded-pill px-4 py-2 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.84rem; font-weight: 600;">
        <i class="bi bi-arrow-repeat"></i> Clear System Cache
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2 small">
        <li>
          <form action="{{ route('admin.config.clear-cache') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="all">
            <button type="submit" class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2 fw-semibold text-danger">
              <i class="bi bi-trash3-fill text-danger"></i> Flush All Caches
            </button>
          </form>
        </li>
        <li><hr class="dropdown-divider my-1"></li>
        <li>
          <form action="{{ route('admin.config.clear-cache') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="views">
            <button type="submit" class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-code text-primary"></i> Clear Blade Views
            </button>
          </form>
        </li>
        <li>
          <form action="{{ route('admin.config.clear-cache') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="routes">
            <button type="submit" class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2">
              <i class="bi bi-signpost-split text-success"></i> Clear Route Cache
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>

<form action="{{ route('admin.config.update') }}" method="POST">
  @csrf

  <div class="row g-4">
    {{-- Left Column: Parameters Forms --}}
    <div class="col-lg-8">

      {{-- Section 1: General Platform Identity --}}
      <div class="portal-card mb-4">
        <div class="d-flex align-items-center gap-2 pb-3 mb-4 border-bottom">
          <div class="rounded-circle d-flex align-items-center justify-content-center text-success" style="width: 36px; height: 36px; background: rgba(20, 83, 45, 0.08);">
            <i class="bi bi-shop fs-5"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-0">Platform Brand & Identity</h5>
            <div class="text-muted" style="font-size: 0.76rem;">Public naming, contact details, and currency configurations</div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="platform_name" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Platform Name <span class="text-danger">*</span></label>
            <input type="text" name="platform_name" id="platform_name" class="form-control rounded-3 py-2 px-3 @error('platform_name') is-invalid @enderror" value="{{ old('platform_name', $settings['platform_name']) }}" required>
            @error('platform_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="platform_tagline" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Motto / Tagline</label>
            <input type="text" name="platform_tagline" id="platform_tagline" class="form-control rounded-3 py-2 px-3 @error('platform_tagline') is-invalid @enderror" value="{{ old('platform_tagline', $settings['platform_tagline']) }}">
            @error('platform_tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="contact_email" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Concierge / Support Email <span class="text-danger">*</span></label>
            <input type="email" name="contact_email" id="contact_email" class="form-control rounded-3 py-2 px-3 @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $settings['contact_email']) }}" required>
            @error('contact_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="contact_phone" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Helpline Phone <span class="text-danger">*</span></label>
            <input type="text" name="contact_phone" id="contact_phone" class="form-control rounded-3 py-2 px-3 @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $settings['contact_phone']) }}" required>
            @error('contact_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="currency_code" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Default Currency Code <span class="text-danger">*</span></label>
            <input type="text" name="currency_code" id="currency_code" class="form-control rounded-3 py-2 px-3 @error('currency_code') is-invalid @enderror" value="{{ old('currency_code', $settings['currency_code']) }}" required placeholder="e.g. PKR">
            @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="currency_symbol" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">Currency Prefix Display <span class="text-danger">*</span></label>
            <input type="text" name="currency_symbol" id="currency_symbol" class="form-control rounded-3 py-2 px-3 @error('currency_symbol') is-invalid @enderror" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" required placeholder="e.g. PKR ">
            @error('currency_symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>

      {{-- Ordering & Cutoff Rules --}}
      <div class="portal-card mb-4">
        <div class="d-flex align-items-center gap-2 pb-3 mb-4 border-bottom">
          <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: rgba(37, 99, 235, 0.08);">
            <i class="bi bi-clock-history fs-5"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-0">Pre-Order & Cutoff Deadlines</h5>
            <div class="text-muted" style="font-size: 0.76rem;">Harvest preparation deadlines and reservation windows</div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label for="default_cutoff_hours" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">
              Default Cutoff Window <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="number" name="default_cutoff_hours" id="default_cutoff_hours" class="form-control rounded-start-3 py-2 px-3 @error('default_cutoff_hours') is-invalid @enderror" value="{{ old('default_cutoff_hours', $settings['default_cutoff_hours']) }}" min="1" max="168" required>
              <span class="input-group-text bg-light text-muted small">Hours Before Slot</span>
            </div>
            <div class="form-text small text-muted" style="font-size: 0.74rem;">Time window before collection when orders lock for picking.</div>
            @error('default_cutoff_hours') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="max_advance_days" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">
              Max Booking Advance <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="number" name="max_advance_days" id="max_advance_days" class="form-control rounded-start-3 py-2 px-3 @error('max_advance_days') is-invalid @enderror" value="{{ old('max_advance_days', $settings['max_advance_days']) }}" min="1" max="60" required>
              <span class="input-group-text bg-light text-muted small">Days in Advance</span>
            </div>
            <div class="form-text small text-muted" style="font-size: 0.74rem;">Maximum future horizon customers can reserve.</div>
            @error('max_advance_days') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="auto_cancel_unconfirmed_hours" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">
              Auto-Cancel Unconfirmed <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="number" name="auto_cancel_unconfirmed_hours" id="auto_cancel_unconfirmed_hours" class="form-control rounded-start-3 py-2 px-3 @error('auto_cancel_unconfirmed_hours') is-invalid @enderror" value="{{ old('auto_cancel_unconfirmed_hours', $settings['auto_cancel_unconfirmed_hours']) }}" min="1" max="72" required>
              <span class="input-group-text bg-light text-muted small">Hours Before Slot</span>
            </div>
            <div class="form-text small text-muted" style="font-size: 0.74rem;">Auto-declines orders if grower doesn't confirm in time.</div>
            @error('auto_cancel_unconfirmed_hours') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="min_order_amount" class="form-label fw-bold text-dark small text-uppercase" style="font-size: 0.74rem;">
              Minimum Order Total (PKR) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light text-muted small">PKR</span>
              <input type="number" name="min_order_amount" id="min_order_amount" class="form-control rounded-end-3 py-2 px-3 @error('min_order_amount') is-invalid @enderror" value="{{ old('min_order_amount', $settings['min_order_amount']) }}" min="0" step="1" required>
            </div>
            <div class="form-text small text-muted" style="font-size: 0.74rem;">Set to 0 for no minimum basket threshold.</div>
            @error('min_order_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>

      {{-- Section 3: Grower & Moderation Protocols --}}
      <div class="portal-card mb-4">
        <div class="d-flex align-items-center gap-2 pb-3 mb-4 border-bottom">
          <div class="rounded-circle d-flex align-items-center justify-content-center text-warning" style="width: 36px; height: 36px; background: rgba(245, 158, 11, 0.08);">
            <i class="bi bi-shield-check fs-5"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-0">Grower Verification & Moderation Protocols</h5>
            <div class="text-muted" style="font-size: 0.76rem;">Platform quality controls, approval gates, and notification alerts</div>
          </div>
        </div>

        <div class="d-flex flex-column gap-3">
          <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small">Require Admin Approval for New Grower Stalls</div>
              <div class="text-muted" style="font-size: 0.75rem;">New farm registrations remain in 'pending' review until community team audits them.</div>
            </div>
            <div class="form-check form-switch fs-5 mb-0">
              <input class="form-check-input" type="checkbox" name="require_grower_approval" value="1" id="require_grower_approval" {{ $settings['require_grower_approval'] == '1' ? 'checked' : '' }}>
            </div>
          </div>

          <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small">Pre-Screen Patron Reviews (Strict Moderation)</div>
              <div class="text-muted" style="font-size: 0.75rem;">Reviews require admin approval before becoming visible on the stall showcase page.</div>
            </div>
            <div class="form-check form-switch fs-5 mb-0">
              <input class="form-check-input" type="checkbox" name="require_review_moderation" value="1" id="require_review_moderation" {{ $settings['require_review_moderation'] == '1' ? 'checked' : '' }}>
            </div>
          </div>

          <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small">Allow Grower Public Responses</div>
              <div class="text-muted" style="font-size: 0.75rem;">Permits verified growers to post official courteous replies to shopper reviews.</div>
            </div>
            <div class="form-check form-switch fs-5 mb-0">
              <input class="form-check-input" type="checkbox" name="allow_farmer_review_replies" value="1" id="allow_farmer_review_replies" {{ $settings['allow_farmer_review_replies'] == '1' ? 'checked' : '' }}>
            </div>
          </div>

          <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between">
            <div>
              <div class="fw-bold text-dark small">Automatic Restock Notifications</div>
              <div class="text-muted" style="font-size: 0.75rem;">Automatically dispatch email/portal alerts to customers who favorited a restocked item.</div>
            </div>
            <div class="form-check form-switch fs-5 mb-0">
              <input class="form-check-input" type="checkbox" name="enable_restock_alerts" value="1" id="enable_restock_alerts" {{ $settings['enable_restock_alerts'] == '1' ? 'checked' : '' }}>
            </div>
          </div>
        </div>
      </div>

      {{-- Save Action --}}
      <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-white border shadow-sm">
        <div class="text-muted small">
          <i class="bi bi-info-circle me-1"></i> Changes take effect immediately across all customer and grower experiences.
        </div>
        <button type="submit" class="btn btn-egreen rounded-pill px-5 py-2 fw-bold text-white shadow-sm" style="background: var(--color-primary, #15803d);">
          <i class="bi bi-check2-circle me-1"></i> Save Platform Settings
        </button>
      </div>

    </div>

    {{-- Right Column: Server & Environmental Diagnostics --}}
    <div class="col-lg-4">

      {{-- Environmental Diagnostics Card --}}
      <div class="portal-card mb-4">
        <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom">
          <i class="bi bi-hdd-network text-success fs-5"></i>
          <h6 class="fw-bold text-dark mb-0">Host & Environment</h6>
        </div>

        <div class="d-flex flex-column gap-3 small">
          <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <span class="text-muted">PHP Engine:</span>
            <span class="fw-bold text-dark badge bg-light text-dark border">{{ $systemInfo['php_version'] }}</span>
          </div>

          <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <span class="text-muted">Laravel Framework:</span>
            <span class="fw-bold text-dark badge bg-light text-dark border">v{{ $systemInfo['laravel_version'] }}</span>
          </div>

          <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <span class="text-muted">Database Engine:</span>
            <span class="fw-semibold text-dark">{{ strtoupper($systemInfo['database_driver']) }}</span>
          </div>

          <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <span class="text-muted">Environment:</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle text-uppercase">{{ $systemInfo['environment'] }}</span>
          </div>

          <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <span class="text-muted">Debug Mode:</span>
            <span class="text-muted">{{ $systemInfo['debug_mode'] }}</span>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">System Timezone:</span>
            <span class="fw-semibold text-dark">{{ $systemInfo['timezone'] }}</span>
          </div>
        </div>
      </div>

      {{-- Quick Cache Operations Card --}}
      <div class="portal-card">
        <div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom">
          <i class="bi bi-speedometer text-primary fs-5"></i>
          <h6 class="fw-bold text-dark mb-0">Performance Maintenance</h6>
        </div>

        <p class="text-muted small mb-3">
          If you update pricing algorithms, weekly schedules, or template views, flush caches to force instant propagation.
        </p>

        <div class="d-grid gap-2">
          <button type="submit" form="flush-all-form" class="btn btn-sm btn-outline-danger rounded-pill py-2 fw-semibold">
            <i class="bi bi-lightning-charge me-1"></i> Flush Complete Cache
          </button>
        </div>
      </div>

    </div>
  </div>
</form>

{{-- Hidden Flush Form --}}
<form id="flush-all-form" action="{{ route('admin.config.clear-cache') }}" method="POST" style="display:none;">
  @csrf
  <input type="hidden" name="type" value="all">
</form>

@endsection
