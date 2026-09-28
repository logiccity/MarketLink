@extends('layouts.app')

@section('title', $farmer->stall_name . ' — Farm Stall Profile & Produce')
@section('meta_description', 'Explore fresh seasonal produce, pickup windows, and certified farm practices of ' . $farmer->stall_name . ' on MarketLink.')

@php
  $bannerUrl = null;
  if (!empty($farmer->banner_image)) {
      $bannerUrl = str_starts_with($farmer->banner_image, 'http')
          ? $farmer->banner_image
          : asset('storage/' . $farmer->banner_image);
  } else {
      $bannerUrl = asset('images/about-hero-farmers.jpg');
  }

  $profileUrl = null;
  if (!empty($farmer->profile_image)) {
      $profileUrl = str_starts_with($farmer->profile_image, 'http')
          ? $farmer->profile_image
          : asset('storage/' . $farmer->profile_image);
  }
@endphp

@section('content')
<div class="producer-showcase" style="background: var(--color-bg); min-height: 90vh;">

  {{-- Producer Hero Showcase --}}
  <div class="farmer-hero-showcase" style="background-image: url('{{ $bannerUrl }}');">
    <div class="farmer-hero-overlay"></div>

    <div class="container position-relative z-2" style="max-width: 1240px;">
      
      {{-- Breadcrumb & Quick Action Row --}}
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0" style="font-size: 0.82rem;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('farmers.index') }}" class="text-white-50 text-decoration-none">Verified Growers</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">{{ $farmer->stall_name }}</li>
          </ol>
        </nav>

        @auth
          @if(auth()->user()->isCustomer())
            <button type="button" class="btn rounded-pill px-4 py-2 fw-bold shadow follow-farmer-btn d-inline-flex align-items-center gap-2" style="background: rgba(255, 255, 255, 0.92); color: var(--color-primary); font-size: 0.85rem; backdrop-filter: blur(8px);" data-farmer-id="{{ $farmer->id }}">
              <i class="bi bi-bell{{ auth()->user()->isFollowing($farmer->id) ? '-fill text-warning' : '' }}"></i>
              <span>{{ auth()->user()->isFollowing($farmer->id) ? 'Subscribed' : 'Follow Stall' }}</span>
            </button>
          @endif
        @endauth
      </div>

      {{-- Main Profile Details Row --}}
      <div class="row align-items-center g-4">
        
        {{-- Stall Avatar Frame --}}
        <div class="col-auto">
          <div class="farmer-avatar-frame">
            @if($profileUrl)
              <img src="{{ $profileUrl }}" alt="{{ $farmer->stall_name }}">
            @else
              <div class="w-100 h-100 d-flex align-items-center justify-content-center fw-bold text-white" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 3.5rem;">
                {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
              </div>
            @endif
          </div>
        </div>

        {{-- Stall Metadata & Credentials --}}
        <div class="col">
          
          {{-- Badges Row --}}
          <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
            <span class="farmer-stall-badge">
              <i class="bi bi-patch-check-fill text-warning"></i>
              <span>Artisanal Producer</span>
            </span>

            @if($farmer->is_organic)
              <span class="badge rounded-pill px-3 py-1 shadow-sm" style="background: #10b981; color: #ffffff; font-size: 0.72rem; font-weight: 700;">
                🌿 Certified Organic
              </span>
            @endif

            @if($farmer->average_rating)
              <span class="badge rounded-pill px-3 py-1 shadow-sm text-white" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.2); font-size: 0.74rem;">
                <i class="bi bi-star-fill text-warning me-1"></i>{{ number_format($farmer->average_rating, 1) }}
                <span class="opacity-75 ms-1">({{ $farmer->reviews_count ?? 0 }} reviews)</span>
              </span>
            @endif
          </div>

          {{-- Stall Name Heading --}}
          <h1 class="display-5 fw-bold text-white mb-2" style="font-family: var(--font-serif); letter-spacing: -0.02em; text-shadow: 0 2px 16px rgba(0,0,0,0.5);">
            {{ $farmer->stall_name }}
          </h1>

          {{-- Grower Person & Location --}}
          <div class="text-white text-opacity-90 small mb-3 d-flex flex-wrap align-items-center gap-2" style="font-size: 0.92rem; text-shadow: 0 1px 6px rgba(0,0,0,0.4);">
            <span>Lead Grower: <strong class="text-white">{{ $farmer->contact_person }}</strong></span>
            @if($farmer->address)
              <span class="opacity-50">&bull;</span>
              <span><i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ $farmer->address }}</span>
            @endif
          </div>

          {{-- Farm Bio / Philosophy --}}
          @if($farmer->bio)
            <p class="text-white text-opacity-90 mb-3 small" style="max-width: 760px; line-height: 1.65; font-size: 0.95rem; text-shadow: 0 1px 8px rgba(0,0,0,0.5);">
              {{ $farmer->bio }}
            </p>
          @endif

          {{-- Operating Parameters Chips --}}
          <div class="d-flex flex-wrap gap-2 gap-md-3 pt-1">
            @if($farmer->pickup_windows)
              <div class="farmer-chip-meta">
                <i class="bi bi-clock-history text-warning"></i>
                <span>Collection Windows: <strong>{{ $farmer->pickup_windows }}</strong></span>
              </div>
            @endif

            @if($farmer->operating_days)
              <div class="farmer-chip-meta">
                <i class="bi bi-calendar3 text-warning"></i>
                <span>Market Days: <strong>{{ implode(', ', (array) $farmer->operating_days) }}</strong></span>
              </div>
            @endif

            <div class="farmer-chip-meta">
              <i class="bi bi-box-seam text-warning"></i>
              <span>Active Offerings: <strong>{{ $farmer->active_products_count ?? $farmer->activeProducts->count() }} Harvest Items</strong></span>
            </div>

            <div class="farmer-chip-meta">
              <i class="bi bi-cash-stack text-success"></i>
              <span>Payment: <strong>Cash in PKR at Stall</strong></span>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  {{-- Market Affiliations Ribbon --}}
  @if($farmer->markets && $farmer->markets->count() > 0)
    <div class="py-3 shadow-sm" style="background: #ffffff; border-bottom: 1px solid var(--color-border-subtle);">
      <div class="container" style="max-width: 1240px;">
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <span class="text-uppercase small fw-bold text-muted d-inline-flex align-items-center gap-1" style="letter-spacing: 0.06em; font-size: 0.74rem;">
            <i class="bi bi-shop-window text-success fs-6"></i> Official Stall Locations:
          </span>
          @foreach($farmer->markets as $market)
            <a href="{{ route('markets.show', $market) }}" class="badge rounded-pill text-decoration-none px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" style="background: var(--color-sage-soft); color: var(--color-primary); font-size: 0.82rem; border: 1px solid rgba(168, 201, 160, 0.4); transition: all 0.2s ease;">
              <span>📍 {{ $market->name }}</span>
              <i class="bi bi-arrow-right-short"></i>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  {{-- Produce Offerings Section --}}
  <div class="container py-5" style="max-width: 1240px;">
    
    {{-- Header & Categories --}}
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4 pb-2 border-bottom" style="border-color: var(--color-border-subtle) !important;">
      <div>
        <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
          Direct From Field
        </span>
        <h2 class="fw-bold text-dark mb-0" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 2.2rem;">
          Stall Harvest Offerings
        </h2>
        <p class="text-muted small mb-0 mt-1">Pre-order now to reserve fresh produce for your preferred market morning collection.</p>
      </div>

      <div>
        <span class="badge rounded-pill px-3 py-2 border small" style="background: #ffffff; color: var(--color-text); font-weight: 600; border-color: var(--color-border) !important;">
          <i class="bi bi-basket2 text-success me-1"></i> {{ $farmer->activeProducts->count() }} Items Available
        </span>
      </div>
    </div>

    {{-- Category Filter Pills --}}
    @if($categories && $categories->count() > 0)
      <div class="d-flex gap-2 flex-wrap mb-4 pb-1">
        <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-sm rounded-pill px-3 py-2 fw-bold {{ !request('category') ? 'btn-lux-primary' : 'btn-light border text-muted' }}" style="font-size: 0.84rem;">
          All Categories
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('farmers.show', $farmer) }}?category={{ $cat->id }}" class="btn btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 {{ request('category') == $cat->id ? 'btn-lux-primary' : 'btn-light border text-muted' }}" style="font-size: 0.84rem;">
            <x-category-icon :category="$cat" size="0.72rem" />
            <span>{{ $cat->name }}</span>
          </a>
        @endforeach
      </div>
    @endif

    {{-- Products Grid --}}
    <div class="row g-4">
      @forelse($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
          <x-product-card :product="$product" />
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background: var(--color-sage-soft); font-size: 2.4rem;">
            🌱
          </div>
          <h4 class="fw-bold text-dark mb-1" style="font-family: var(--font-serif);">No Active Offerings</h4>
          <p class="text-muted small mx-auto" style="max-width: 440px; line-height: 1.6;">
            This producer has no items currently active for pre-order in this category. Check back ahead of market day when new dawn crops are listed.
          </p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
      <div class="mt-5 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
      </div>
    @endif

  </div>

  {{-- Freshness Guarantee Banner --}}
  <div class="container mb-5" style="max-width: 1240px;">
    <div class="p-4 p-md-5 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #123C2F 0%, #1A4D3D 100%); color: #ffffff;">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(255, 255, 255, 0.15); color: #EBDDC0; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
            MarketLink Community Promise
          </span>
          <h3 class="fw-bold text-white mb-2" style="font-family: var(--font-serif); font-size: 1.8rem;">
            Peak Harvest Freshness &bull; Direct Stall Handshake
          </h3>
          <p class="text-white-50 mb-0 small" style="line-height: 1.65; max-width: 680px; font-size: 0.94rem;">
            Every reservation with {{ $farmer->stall_name }} is plucked fresh at dawn specifically for you. Inspect your produce crate directly at the stall upon arrival and settle cash in Pakistani Rupee (PKR). 100% of your payment stays with the grower.
          </p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="{{ route('faq') }}" class="btn btn-sm rounded-pill px-4 py-3 fw-bold text-white" style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); font-size: 0.9rem;">
            <i class="bi bi-shield-check text-warning me-1"></i> How Stall Pickup Works
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Patron Reviews & Testimonials --}}
  @if($reviews->count() > 0)
    <div class="py-5" style="background: #ffffff; border-top: 1px solid var(--color-border-subtle);">
      <div class="container" style="max-width: 1240px;">
        
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: var(--color-border-subtle) !important;">
          <div>
            <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
              Community Verification
            </span>
            <h3 class="fw-bold text-dark mb-0" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 1.8rem;">
              Patron Reviews &amp; Feedback ({{ $reviews->total() }})
            </h3>
          </div>
        </div>

        <div class="row g-4">
          @foreach($reviews as $review)
            <div class="col-md-6">
              <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background: #fbfcfb; border: 1px solid var(--color-border-subtle) !important;">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px; flex-shrink: 0; background: linear-gradient(135deg, #166534, #15803d); font-size: 1.1rem;">
                    {{ strtoupper(substr($review->customer->user->name ?? 'P', 0, 1)) }}
                  </div>
                  <div>
                    <div class="fw-bold text-dark mb-0">{{ $review->customer->user->name ?? 'Verified Patron' }}</div>
                    <div class="text-muted small" style="font-size: 0.74rem;">{{ $review->created_at->format('M j, Y') }}</div>
                  </div>
                  <div class="ms-auto d-flex gap-1 text-warning">
                    @for($i = 1; $i <= 5; $i++)
                      <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}" style="font-size: 0.85rem;"></i>
                    @endfor
                  </div>
                </div>

                @if($review->product)
                  <div class="small fw-semibold mb-2" style="color: var(--color-secondary); font-size: 0.82rem;">
                    <i class="bi bi-box-seam me-1"></i> Harvested: {{ $review->product->name }}
                  </div>
                @endif

                @if($review->comment)
                  <p class="text-secondary small mb-0 fst-italic" style="font-size: 0.88rem; line-height: 1.6;">
                    "{{ $review->comment }}"
                  </p>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        @if($reviews->hasPages())
          <div class="mt-4 d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
          </div>
        @endif

      </div>
    </div>
  @endif

</div>
@endsection
