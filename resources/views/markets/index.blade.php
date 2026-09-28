@extends('layouts.app')

@section('title', 'Community Farmers Markets — MarketLink')

@section('content')
<div class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(20, 83, 45, 0.04) 0%, rgba(248, 250, 252, 0.95) 90%); min-height: 85vh;">
  <div class="container" style="max-width: 1240px;">

    {{-- Editorial Header --}}
    <div class="row align-items-end justify-content-between mb-5 g-4">
      <div class="col-lg-7">
        <span class="badge rounded-pill px-3 py-2 text-uppercase mb-3" style="background: rgba(20, 83, 45, 0.08); color: #14532d; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
          Local Gathering Grounds
        </span>
        <h1 class="fw-bold text-dark mb-2" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.02em; font-size: 2.6rem;">
          Farmers Market Hubs
        </h1>
        <p class="text-muted mb-0" style="font-size: 1.05rem; line-height: 1.6;">
          Discover regional market venues hosting verified agrarian growers. Select your pickup community, inspect stall rosters, and reserve dawn-picked produce.
        </p>
      </div>

      <div class="col-lg-4">
        <form action="{{ route('markets.index') }}" method="GET" class="p-2 rounded-pill shadow-sm" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08);">
          <div class="d-flex align-items-center">
            <i class="bi bi-search text-muted ps-3 pe-2"></i>
            <input type="text" name="search" class="form-control border-0 shadow-none py-2 px-1 text-dark" placeholder="Search markets or districts..." value="{{ request('search') }}" style="font-size: 0.9rem;">
            <button type="submit" class="btn rounded-pill px-4 py-2 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.88rem;">
              Search
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Content Layout: Map + Markets List --}}
    <div class="row g-5">
      {{-- Map Section --}}
      <div class="col-lg-5 order-lg-2">
        <div class="sticky-top" style="top: 100px;">
          <div class="card border-0 rounded-4 overflow-hidden shadow-sm mb-3" style="height: 440px; border: 1px solid rgba(0,0,0,0.08);">
            <div id="markets-map" style="height: 100%; width: 100%;"></div>
          </div>
          <p class="text-muted small text-center d-flex align-items-center justify-content-center gap-1">
            <i class="bi bi-geo-alt-fill text-success"></i>
            <span>Interactive Venue Map &bull; Select a pin to reveal market stalls</span>
          </p>
        </div>
      </div>

      {{-- Markets List --}}
      <div class="col-lg-7 order-lg-1">
        @forelse($markets as $market)
          <a href="{{ route('markets.show', $market) }}" class="text-decoration-none d-block mb-4">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden hover-lift" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.05); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
              <div class="row g-0">
                {{-- Left Image / Accent --}}
                <div class="col-sm-4 position-relative overflow-hidden">
                  @if($market->image_url)
                    <div class="h-100 w-100 position-relative" style="min-height: 180px;">
                      <img src="{{ $market->image_url }}" alt="{{ $market->name }}" class="w-100 h-100" style="object-fit: cover; position: absolute; inset: 0;">
                      <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(14, 43, 33, 0.75) 100%);"></div>
                      <div class="position-absolute bottom-0 start-0 p-3 text-white">
                        <div class="fw-bold small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.74rem;">
                          {{ implode(' &bull; ', array_slice($market->market_days ?? [], 0, 2)) }}
                        </div>
                        @if($market->is_active)
                          <span class="badge rounded-pill px-2 py-1 mt-1 text-uppercase shadow-sm" style="background: #10b981; font-size: 0.65rem; font-weight: 700;">
                            Open Market
                          </span>
                        @else
                          <span class="badge bg-secondary rounded-pill mt-1" style="font-size: 0.65rem;">Seasonal</span>
                        @endif
                      </div>
                    </div>
                  @else
                    <div class="h-100 p-4 d-flex flex-column align-items-center justify-content-center text-center text-white" style="min-height: 170px; background: linear-gradient(135deg, #14532d 0%, #166534 100%);">
                      <div style="font-size: 2.8rem; line-height: 1;">🏪</div>
                      <div class="fw-bold mt-2 small text-uppercase" style="letter-spacing: 0.05em; font-size: 0.78rem;">
                        {{ implode(' &bull; ', array_slice($market->market_days ?? [], 0, 2)) }}
                      </div>
                      @if($market->is_active)
                        <span class="badge rounded-pill px-3 py-1 mt-2 text-uppercase shadow-sm" style="background: rgba(255,255,255,0.2); font-size: 0.68rem; letter-spacing: 0.05em; font-weight: 700;">
                          Open Market
                        </span>
                      @else
                        <span class="badge bg-secondary rounded-pill mt-2">Seasonal Recess</span>
                      @endif
                    </div>
                  @endif
                </div>

                {{-- Right Content --}}
                <div class="col-sm-8">
                  <div class="card-body p-4 d-flex flex-column h-100">
                    <div class="d-flex align-items-start justify-content-between mb-1">
                      <h4 class="fw-bold text-dark mb-0" style="font-size: 1.25rem; letter-spacing: -0.01em;">
                        {{ $market->name }}
                      </h4>
                    </div>

                    <div class="text-muted small mb-3 d-flex align-items-center gap-1" style="font-size: 0.84rem;">
                      <i class="bi bi-geo-alt-fill text-success"></i>
                      <span>{{ $market->location }}</span>
                    </div>

                    @if($market->description)
                      <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.84rem; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $market->description }}
                      </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2 pt-2 border-top mt-auto align-items-center justify-content-between">
                      <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(20, 83, 45, 0.08); color: #166534; font-weight: 700; font-size: 0.76rem;">
                          <i class="bi bi-shop me-1"></i>{{ $market->farmers_count ?? 0 }} Farm Stalls
                        </span>
                        @if($market->opening_time)
                          <span class="text-muted small d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                            <i class="bi bi-clock"></i>
                            <span>{{ $market->opening_time }} – {{ $market->closing_time }}</span>
                          </span>
                        @endif
                      </div>

                      <span class="text-success small fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.82rem;">
                        <span>Explore Stalls</span>
                        <i class="bi bi-arrow-right"></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        @empty
          <div class="card border-0 rounded-4 p-5 text-center shadow-sm" style="background: #ffffff;">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 mx-auto" style="width: 70px; height: 70px; background: rgba(20, 83, 45, 0.06); font-size: 2rem;">
              🏪
            </div>
            <h4 class="fw-bold text-dark">No Markets Found</h4>
            <p class="text-muted small mx-auto mb-3" style="max-width: 360px;">No farmers market hubs currently match your district search query.</p>
            <a href="{{ route('markets.index') }}" class="btn rounded-pill px-4 py-2 fw-semibold text-white mx-auto" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.88rem; width: fit-content;">
              View All Markets
            </a>
          </div>
        @endforelse

        <div class="mt-4">
          {{ $markets->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapElement = document.getElementById('markets-map');
  if (!mapElement) return;

  const map = L.map('markets-map').setView([30.3753, 69.3451], 6);
  L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '&copy; Google Maps'
  }).addTo(map);

  const greenIcon = L.divIcon({
    className: '',
    html: `<div style="background: #15803d; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
            <div style="transform: rotate(45deg); font-size: 13px; color: #fff;">🏪</div>
          </div>`,
    iconSize: [34, 34],
    iconAnchor: [17, 34]
  });

  const markers = [];

  @foreach($markets as $market)
    @if($market->latitude && $market->longitude)
      const marker{{ $market->id }} = L.marker([{{ $market->latitude }}, {{ $market->longitude }}], {icon: greenIcon})
        .addTo(map)
        .bindPopup(`
          <div style="font-family: inherit; padding: 4px;">
            <h6 style="font-weight: 700; margin-bottom: 4px; color: #14532d;">{{ $market->name }}</h6>
            <div style="font-size: 11px; color: #64748b; margin-bottom: 8px;">{{ $market->location }}</div>
            <a href="{{ route('markets.show', $market) }}" class="btn btn-sm text-white w-100 rounded-pill" style="background: #15803d; font-size: 11px; font-weight: 600; padding: 4px 10px;">
              View Stalls &rarr;
            </a>
          </div>
        `);
      markers.push([{{ $market->latitude }}, {{ $market->longitude }}]);
    @endif
  @endforeach

  if (markers.length > 0) {
    const bounds = L.latLngBounds(markers);
    map.fitBounds(bounds, { padding: [40, 40] });
  }
});
</script>
@endpush
