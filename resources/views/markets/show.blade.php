@extends('layouts.app')

@section('title', $market->name . ' — Farmers Market Venue')

@section('content')
<div class="market-venue-page" style="background: radial-gradient(circle at 10% 20%, rgba(20, 83, 45, 0.04) 0%, rgba(248, 250, 252, 0.95) 90%); min-height: 90vh;">

  @php
    $bannerUrl = $market->image_url ?? 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?auto=format&fit=crop&w=1600&q=80';
  @endphp

  {{-- Hero Header with Uploaded Market Venue Banner --}}
  <div class="position-relative overflow-hidden market-hero-banner" style="background-color: #071712; color: #ffffff; padding: 4.75rem 0 4rem; min-height: 400px;">
    {{-- Banner Image Background Layer --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('{{ $bannerUrl }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; filter: brightness(0.92) saturate(1.1); transform: scale(1.02); transition: transform 0.6s ease;"></div>

    {{-- Balanced Filmic Overlay: Keeps image clearly visible while ensuring text contrast --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(6, 20, 14, 0.4) 0%, rgba(6, 20, 14, 0.55) 45%, rgba(4, 14, 10, 0.88) 100%), linear-gradient(90deg, rgba(4, 14, 10, 0.82) 0%, rgba(4, 14, 10, 0.52) 55%, rgba(4, 14, 10, 0.22) 100%);"></div>

    <div class="container position-relative" style="max-width: 1240px; z-index: 2;">
      {{-- Breadcrumb --}}
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small" style="color: rgba(255,255,255,0.85);">
          <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none fw-semibold"><i class="bi bi-house-door me-1"></i>Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('markets.index') }}" class="text-white text-decoration-none fw-semibold">Markets</a></li>
          <li class="breadcrumb-item active text-white fw-bold" aria-current="page">{{ $market->name }}</li>
        </ol>
      </nav>

      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="badge rounded-pill px-3 py-1 text-uppercase shadow-sm" style="background: rgba(0, 0, 0, 0.55); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.3); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700; color: #ffffff;">
              <i class="bi bi-pin-map-fill text-warning me-1"></i>Community Market Hub
            </span>
            @if($market->is_active)
              <span class="badge rounded-pill px-3 py-1 shadow-sm" style="background: #10b981; color: #ffffff; font-size: 0.74rem; font-weight: 700;">
                <i class="bi bi-patch-check-fill me-1"></i>Verified Active
              </span>
            @endif
          </div>

          <h1 class="fw-bold mb-3" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.02em; font-size: clamp(2.2rem, 4vw, 3.2rem); color: #ffffff !important; text-shadow: 0 3px 18px rgba(0, 0, 0, 0.75), 0 1px 3px rgba(0, 0, 0, 0.6); line-height: 1.15;">
            {{ $market->name }}
          </h1>

          {{-- Meta Info Badges Bar --}}
          <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            {{-- Location Pill --}}
            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm" style="background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.32);">
              <i class="bi bi-geo-alt-fill text-warning fs-5"></i>
              <span class="text-white fw-bold" style="font-size: 0.95rem; letter-spacing: 0.01em; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">{{ $market->location }}</span>
            </div>

            {{-- Opening Hours Pill --}}
            @if($market->opening_time)
              <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm" style="background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.32);">
                <i class="bi bi-clock-fill text-warning fs-6"></i>
                <span class="text-white fw-semibold" style="font-size: 0.9rem; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">{{ $market->opening_time }} – {{ $market->closing_time }}</span>
              </div>
            @endif

            {{-- Farm Stalls Pill --}}
            <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm" style="background: rgba(0, 0, 0, 0.58); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.32);">
              <i class="bi bi-shop text-warning fs-6"></i>
              <span class="text-white fw-semibold" style="font-size: 0.9rem; text-shadow: 0 1px 3px rgba(0,0,0,0.5);">{{ $market->farmers->count() }} Registered Farm Stalls</span>
            </div>
          </div>

          @if($market->description)
            <div class="p-3 rounded-4 mt-3" style="background: rgba(0, 0, 0, 0.48); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.22); max-width: 720px;">
              <p class="text-white mb-0" style="font-size: 0.98rem; line-height: 1.6; font-weight: 400; text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
                {{ $market->description }}
              </p>
            </div>
          @endif
        </div>

        <div class="col-lg-4 text-lg-end">
          <div class="d-flex flex-wrap gap-2 justify-content-lg-end mb-3">
            @foreach($market->market_days ?? [] as $day)
              <span class="badge rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.35); font-size: 0.9rem; font-weight: 700; color: #ffffff;">
                <span>🗓️</span> <span>{{ $day }}</span>
              </span>
            @endforeach
          </div>

          {{-- Preferred Market Toggle --}}
          @auth
            @if(auth()->user()->isCustomer())
              @php
                $isFavMarket = auth()->user()->customer?->hasFavoritedMarket($market->id);
              @endphp
              <form action="{{ route('customer.favorites.market.toggle', $market) }}" method="POST" class="d-inline-block">
                @csrf
                <button type="submit" class="btn btn-sm rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: {{ $isFavMarket ? '#ef4444' : 'rgba(0, 0, 0, 0.55)' }}; backdrop-filter: blur(10px); color: #ffffff; border: 1px solid rgba(255,255,255,0.35);">
                  <i class="bi bi-heart{{ $isFavMarket ? '-fill' : '' }} text-warning"></i>
                  <span>{{ $isFavMarket ? 'Preferred Market Saved' : 'Save as Preferred Market' }}</span>
                </button>
              </form>
            @endif
          @endauth
        </div>
      </div>
    </div>
  </div>

  {{-- Main Content Grid --}}
  <div class="container py-5" style="max-width: 1240px;">
    <div class="row g-5">

      {{-- Left Column: Farm Stalls Listing --}}
      <div class="col-lg-8 order-lg-1">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
          <div>
            <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(20, 83, 45, 0.08); color: #14532d; font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">
              Artisanal Roster
            </span>
            <h3 class="fw-bold text-dark mb-0" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em;">
              Participating Farm Stalls ({{ $farmers->count() }})
            </h3>
          </div>
        </div>

        @forelse($farmers as $farmer)
          <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden hover-lift" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.06); transition: all 0.25s ease;">
            <div class="card-body p-4">
              <div class="row align-items-start g-3">
                {{-- Stall Avatar --}}
                <div class="col-auto">
                  <div class="rounded-4 overflow-hidden shadow-sm" style="width: 76px; height: 76px; flex-shrink: 0; background: #f8fafc;">
                    @if($farmer->profile_image)
                      <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                      <div class="w-100 h-100 d-flex align-items-center justify-content-center fw-bold text-white" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 2rem;">
                        {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                      </div>
                    @endif
                  </div>
                </div>

                {{-- Stall Body --}}
                <div class="col">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                    <h4 class="fw-bold text-dark mb-0" style="font-size: 1.2rem; letter-spacing: -0.01em;">
                      {{ $farmer->stall_name }}
                    </h4>
                    @if($farmer->average_rating)
                      <div class="d-flex align-items-center gap-1 text-warning small">
                        <i class="bi bi-star-fill"></i>
                        <span class="fw-bold text-dark">{{ number_format($farmer->average_rating, 1) }}</span>
                        <span class="text-muted small">({{ $farmer->reviews_count ?? 0 }} reviews)</span>
                      </div>
                    @endif
                  </div>

                  @if($farmer->bio)
                    <p class="text-muted small mb-2" style="font-size: 0.84rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                      {{ $farmer->bio }}
                    </p>
                  @endif

                  <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(20, 83, 45, 0.08); color: #166534; font-weight: 700; font-size: 0.74rem;">
                      {{ $farmer->active_products_count ?? 0 }} Varieties Listed
                    </span>
                    @if($farmer->is_organic)
                      <span class="badge rounded-pill px-3 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-weight: 700; font-size: 0.74rem;">
                        🌿 Certified Organic
                      </span>
                    @endif
                  </div>

                  {{-- Sample Produce Items --}}
                  @if($farmer->activeProducts && $farmer->activeProducts->count() > 0)
                    <div class="d-flex flex-wrap gap-2 mb-3">
                      @foreach($farmer->activeProducts->take(4) as $product)
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none">
                          <span class="badge rounded-pill px-3 py-2 border shadow-none" style="background: #f8fafc; color: #334155; font-size: 0.78rem; font-weight: 600;">
                            {{ $product->name }} &bull; PKR {{ number_format($product->price_per_unit ?? $product->price, 2) }}
                          </span>
                        </a>
                      @endforeach
                    </div>
                  @endif

                  <div>
                    <a href="{{ route('farmers.show', $farmer) }}" class="btn btn-sm btn-outline-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.82rem;">
                      <span>Explore Farm Stall</span>
                      <i class="bi bi-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="card border-0 rounded-4 p-5 text-center shadow-sm" style="background: #ffffff;">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto" style="width: 70px; height: 70px; background: rgba(20, 83, 45, 0.06); font-size: 2rem;">
              🌾
            </div>
            <h4 class="fw-bold text-dark mb-1">No Farm Stalls Assigned</h4>
            <p class="text-muted small mx-auto mb-0" style="max-width: 360px;">No growers are currently listed for this market location. Stall allocations are updated weekly.</p>
          </div>
        @endforelse
      </div>

      {{-- Right Column: Map & Logistics Sidebar --}}
      <div class="col-lg-4 order-lg-2">
        <div class="sticky-top" style="top: 100px;">
          {{-- Map Card & Route Directions --}}
          @if($market->latitude && $market->longitude)
            <div class="card border-0 rounded-4 overflow-hidden shadow-sm mb-3" style="height: 250px; border: 1px solid rgba(0,0,0,0.06);">
              <div id="market-map" style="height: 100%; width: 100%;"></div>
            </div>
            <div class="mb-4">
              <a href="https://www.google.com/maps/dir/?api=1&destination={{ $market->latitude }},{{ $market->longitude }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success w-100 rounded-pill py-2 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm" style="font-size: 0.86rem; border-color: #166534;">
                <i class="bi bi-compass-fill text-success"></i>
                <span>Get Route Directions (Google Maps)</span>
                <i class="bi bi-box-arrow-up-right small"></i>
              </a>
            </div>
          @endif

          {{-- Market Logistics Card --}}
          <div class="card border-0 rounded-4 shadow-sm mb-4" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.06);">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
              <h5 class="fw-bold text-dark mb-0">Venue Coordinates</h5>
            </div>
            <div class="card-body px-4 pb-4">
              <div class="d-flex flex-column gap-3">
                <div class="d-flex align-items-start gap-3">
                  <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(20, 83, 45, 0.08); color: #15803d;">
                    <i class="bi bi-calendar3"></i>
                  </div>
                  <div>
                    <div class="small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Market Days</div>
                    <div class="small text-muted">{{ implode(', ', $market->market_days ?? []) }}</div>
                  </div>
                </div>

                @if($market->opening_time)
                  <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(20, 83, 45, 0.08); color: #15803d;">
                      <i class="bi bi-clock"></i>
                    </div>
                    <div>
                      <div class="small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Collection Window</div>
                      <div class="small text-muted">{{ $market->opening_time }} – {{ $market->closing_time }}</div>
                    </div>
                  </div>
                @endif

                <div class="d-flex align-items-start gap-3">
                  <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(20, 83, 45, 0.08); color: #15803d;">
                    <i class="bi bi-geo-alt"></i>
                  </div>
                  <div>
                    <div class="small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Physical Location</div>
                    <div class="small text-muted">{{ $market->full_address ?? $market->location }}</div>
                  </div>
                </div>

                @if($market->contact_phone)
                  <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: rgba(20, 83, 45, 0.08); color: #15803d;">
                      <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                      <div class="small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Desk Contact</div>
                      <div class="small text-muted">{{ $market->contact_phone }}</div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>

          {{-- Cutoff Warning --}}
          @if($market->order_cutoff_hours)
            <div class="alert border-0 rounded-4 shadow-sm mb-4 p-3 d-flex align-items-start gap-3" style="background: rgba(217, 119, 6, 0.08); border-left: 4px solid #d97706 !important; color: #92400e;">
              <i class="bi bi-clock-history fs-5 text-warning flex-shrink-0"></i>
              <div class="small" style="font-size: 0.8rem; line-height: 1.45;">
                <strong>Harvest Cutoff:</strong> Pre-orders must be submitted at least <strong>{{ $market->order_cutoff_hours }} hours</strong> before market commencement to ensure morning harvest.
              </div>
            </div>
          @endif

          <a href="{{ route('products.index', ['market' => $market->id]) }}" class="btn w-100 rounded-pill py-3 fw-bold text-white shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.92rem;">
            <i class="bi bi-basket2-fill"></i>
            <span>Browse All Produce at This Venue</span>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
@if($market->latitude && $market->longitude)
document.addEventListener('DOMContentLoaded', function() {
  const mapEl = document.getElementById('market-map');
  if (!mapEl) return;

  const map = L.map('market-map').setView([{{ $market->latitude }}, {{ $market->longitude }}], 15);
  L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '&copy; Google Maps'
  }).addTo(map);

  const marker = L.marker([{{ $market->latitude }}, {{ $market->longitude }}])
    .addTo(map)
    .bindPopup('<strong>{{ $market->name }}</strong><br><small>{{ $market->location }}</small>')
    .openPopup();
});
@endif
</script>
@endpush
