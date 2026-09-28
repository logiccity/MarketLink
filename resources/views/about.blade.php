@extends('layouts.app')

@section('title', 'About MarketLink — Soil, Stalls & Community')
@section('meta_description', 'Learn about MarketLink by eGreen Basket — connecting independent organic farmers directly with mindful consumers through transparent pre-orders.')

@section('content')
{{-- Hero Banner --}}
<section class="page-hero-banner py-5 py-lg-6" style="background-image: url('{{ asset('images/about-hero-farmers.jpg') }}');">
  <div class="container hero-content-rel">
    <div class="row align-items-center g-4 g-lg-5">
      
      {{-- Left: Text & Purpose --}}
      <div class="col-lg-7">
        <div class="d-inline-flex mb-3">
          <span class="hero-tag-badge">
            <i class="bi bi-shield-check text-warning"></i>
            <span>Our Purpose &bull; 100% Direct From Farmers</span>
          </span>
        </div>

        <h1 class="display-5 fw-bold text-white mb-3" style="font-family: var(--font-serif); letter-spacing: -0.02em; line-height: 1.2;">
          Connecting Living Soil, Honest Stalls &amp; <span style="color: #D4B477;">Conscious Tables</span>
        </h1>

        <p class="lead text-white-50 mb-4" style="font-size: 1.1rem; line-height: 1.7; max-width: 640px;">
          MarketLink by eGreen Basket revolutionizes decentralized community food networks. We empower independent regenerative growers with an ultra-transparent pre-reservation system that eliminates industrial middlemen, secures fair farmgate returns, and brings morning-picked harvest directly to your table.
        </p>

        {{-- Trust Value Highlights --}}
        <div class="d-flex flex-wrap align-items-center gap-3 mb-4 text-white-50 small" style="font-weight: 500;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="text-white">Zero Middleman Markup</span>
          </div>
          <span class="opacity-25">&bull;</span>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="text-white">Dawn Harvest Freshness</span>
          </div>
          <span class="opacity-25">&bull;</span>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="text-white">Direct Stall Cash Exchange</span>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex flex-wrap align-items-center gap-3">
          <a href="{{ route('products.index') }}" class="btn btn-lux-primary px-4 py-2" style="font-size: 0.95rem; border-radius: var(--radius-pill); font-weight: 600;">
            <i class="bi bi-basket2 me-2"></i>Explore Seasonal Produce
          </a>
          <a href="{{ route('markets.index') }}" class="btn px-4 py-2 text-white" style="border: 1px solid rgba(255,255,255,0.3); border-radius: var(--radius-pill); font-size: 0.95rem; font-weight: 600; background: rgba(255,255,255,0.08); backdrop-filter: blur(6px);">
            <i class="bi bi-geo-alt me-2 text-warning"></i>Find Community Markets
          </a>
        </div>
      </div>

      {{-- Right: High-Res Farmer Portrait Card --}}
      <div class="col-lg-5">
        <div class="hero-photo-frame">
          <img src="{{ asset('images/about-hero-farmers.jpg') }}" alt="Organic farmer with freshly harvested seasonal vegetables" class="img-fluid">
          <div class="hero-floating-glass-card">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-success flex-shrink-0" style="width: 42px; height: 42px; background: rgba(255,255,255,0.15); font-size: 1.25rem;">
              <i class="bi bi-patch-check-fill text-warning"></i>
            </div>
            <div>
              <div class="fw-bold" style="font-size: 0.88rem; letter-spacing: -0.01em;">Verified Local Producers</div>
              <div class="small opacity-75" style="font-size: 0.76rem;">Harvested fresh daily at dawn across community market stalls</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- Three Pillars --}}
<section class="py-5 py-lg-6" style="background: var(--color-bg);">
  <div class="container" style="max-width: 1200px;">
    
    <div class="text-center mb-5 pb-2">
      <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
        Foundational Philosophy
      </span>
      <h2 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 2.2rem;">
        Why MarketLink Exists
      </h2>
      <p class="text-muted mx-auto" style="max-width: 580px; font-size: 0.96rem; line-height: 1.6;">
        Engineered from the ground up to protect regional family agriculture and deliver uncompromised peak harvest freshness directly to neighborhood stalls.
      </p>
    </div>

    <div class="row g-4 align-items-stretch">
      
      {{-- Pillar 1 --}}
      <div class="col-lg-4 col-md-6">
        <div class="pillar-card-lux">
          <div class="pillar-icon-box">
            <i class="bi bi-flower1"></i>
          </div>
          <h4 class="fw-bold text-dark mb-3" style="font-size: 1.25rem; font-family: var(--font-serif);">Grower Sovereignty</h4>
          <p class="text-muted small mb-0" style="line-height: 1.7; font-size: 0.9rem;">
            Producers know precisely what is spoken for before dawn harvest. Zero surplus rot, guaranteed fair farmgate returns, and direct community appreciation without commission deductions or digital tollgates.
          </p>
        </div>
      </div>

      {{-- Pillar 2 --}}
      <div class="col-lg-4 col-md-6">
        <div class="pillar-card-lux">
          <div class="pillar-icon-box">
            <i class="bi bi-sun"></i>
          </div>
          <h4 class="fw-bold text-dark mb-3" style="font-size: 1.25rem; font-family: var(--font-serif);">Dawn Harvest Freshness</h4>
          <p class="text-muted small mb-0" style="line-height: 1.7; font-size: 0.9rem;">
            Heirloom crops plucked the exact morning you receive them at the stall. Rediscover profound seasonal nutrition untouched by long-haul cold storage freight lines and multi-day warehouse delays.
          </p>
        </div>
      </div>

      {{-- Pillar 3 --}}
      <div class="col-lg-4 col-md-6 mx-auto">
        <div class="pillar-card-lux">
          <div class="pillar-icon-box">
            <i class="bi bi-cash-stack"></i>
          </div>
          <h4 class="fw-bold text-dark mb-3" style="font-size: 1.25rem; font-family: var(--font-serif);">Direct Stall Cash Exchange</h4>
          <p class="text-muted small mb-0" style="line-height: 1.7; font-size: 0.9rem;">
            Zero hidden payment processing fees or banking intermediaries. Reserve effortlessly through our digital interface and settle directly with your grower in cash at their stall upon collection.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- How MarketLink Works --}}
<section class="py-5 py-lg-6" style="background: #ffffff; border-top: 1px solid var(--color-border-subtle); border-bottom: 1px solid var(--color-border-subtle);">
  <div class="container" style="max-width: 1140px;">
    
    <div class="text-center mb-5">
      <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
        Simple &amp; Transparent
      </span>
      <h2 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif); font-size: 2.2rem;">
        From Living Soil to Market Stall
      </h2>
      <p class="text-muted mx-auto" style="max-width: 520px; font-size: 0.96rem;">
        Our harvest-to-order model eliminates food waste while guaranteeing patrons the freshest produce possible.
      </p>
    </div>

    <div class="row g-4">
      
      {{-- Step 1 --}}
      <div class="col-md-4">
        <div class="p-4 rounded-4 text-center h-100" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border-subtle);">
          <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 fw-bold" style="width: 50px; height: 50px; background: var(--color-primary); color: #D4B477; font-size: 1.15rem;">
            1
          </div>
          <h5 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif);">Patrons Pre-Order</h5>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            Browse weekly harvest inventories from certified local farmers and reserve your produce bundle online with zero upfront deposit.
          </p>
        </div>
      </div>

      {{-- Step 2 --}}
      <div class="col-md-4">
        <div class="p-4 rounded-4 text-center h-100" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border-subtle);">
          <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 fw-bold" style="width: 50px; height: 50px; background: var(--color-primary); color: #D4B477; font-size: 1.15rem;">
            2
          </div>
          <h5 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif);">Dawn Harvest</h5>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            Growers harvest only the exact portions spoken for at daybreak and pack fresh crates directly for the morning market stalls.
          </p>
        </div>
      </div>

      {{-- Step 3 --}}
      <div class="col-md-4">
        <div class="p-4 rounded-4 text-center h-100" style="background: var(--color-bg-secondary); border: 1px solid var(--color-border-subtle);">
          <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3 fw-bold" style="width: 50px; height: 50px; background: var(--color-primary); color: #D4B477; font-size: 1.15rem;">
            3
          </div>
          <h5 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif);">Stall Pickup &amp; Cash</h5>
          <p class="text-muted small mb-0" style="line-height: 1.6;">
            Visit your designated market stall, meet your grower face-to-face, inspect your harvest, and settle in cash at the stall.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- Impact Numbers --}}
<section class="py-5" style="background: linear-gradient(135deg, #123C2F 0%, #0B271E 100%); color: #ffffff;">
  <div class="container" style="max-width: 1040px;">
    <div class="row text-center g-4 justify-content-center">
      
      <div class="col-md-4 col-12">
        <div class="impact-counter-card">
          <div class="impact-counter-val">
            {{ $stats['markets_count'] ?? 3 }}+
          </div>
          <div class="impact-counter-lbl">Community Market Hubs</div>
          <div class="small opacity-50 mt-1">Connecting urban patrons with rural growers</div>
        </div>
      </div>

      <div class="col-md-4 col-12">
        <div class="impact-counter-card">
          <div class="impact-counter-val">
            {{ $stats['farmers_count'] ?? 10 }}+
          </div>
          <div class="impact-counter-lbl">Certified Local Growers</div>
          <div class="small opacity-50 mt-1">Vetted for sustainable &amp; organic farming</div>
        </div>
      </div>

      <div class="col-md-4 col-12">
        <div class="impact-counter-card">
          <div class="impact-counter-val">
            {{ $stats['products_count'] ?? 40 }}+
          </div>
          <div class="impact-counter-lbl">Fresh Produce Varieties</div>
          <div class="small opacity-50 mt-1">Heirloom vegetables, seasonal fruits &amp; herbs</div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- Call to Action --}}
<section class="py-5 py-lg-6" style="background: var(--color-bg);">
  <div class="container text-center py-4" style="max-width: 720px;">
    <span class="badge rounded-pill px-3 py-1 mb-3 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
      Join the Food Revolution
    </span>
    <h2 class="fw-bold text-dark mb-3" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 2.2rem;">
      Experience Real Food from Real Hands
    </h2>
    <p class="text-muted mb-4 mx-auto" style="font-size: 1rem; line-height: 1.7;">
      Join thousands of mindful patrons enjoying artisanal harvest reservations every weekend across regional farmers markets. Support your local agricultural ecosystem.
    </p>
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
      <a href="{{ route('products.index') }}" class="btn btn-lux-primary px-4 py-3" style="font-size: 0.95rem; border-radius: var(--radius-pill); font-weight: 600;">
        <i class="bi bi-basket2-fill me-2"></i>Explore Seasonal Produce
      </a>
      <a href="{{ route('register.farmer') }}" class="btn btn-lux-secondary px-4 py-3" style="font-size: 0.95rem; border-radius: var(--radius-pill); font-weight: 600;">
        <i class="bi bi-shop me-2"></i>Enroll as a Grower
      </a>
    </div>
  </div>
</section>
@endsection
