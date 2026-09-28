@extends('layouts.app')

@section('title', 'Artisanal Producers & Farm Stalls — MarketLink')

@section('content')
<div class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(20, 83, 45, 0.04) 0%, rgba(248, 250, 252, 0.95) 90%); min-height: 85vh;">
  <div class="container" style="max-width: 1240px;">

    {{-- Editorial Header --}}
    <div class="text-center mb-5 pb-3">
      <div class="badge rounded-pill px-3 py-2 text-uppercase mb-3" style="background: rgba(20, 83, 45, 0.08); color: #14532d; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
        Independent Agrarian Guild
      </div>
      <h1 class="fw-bold text-dark mb-3" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.02em; font-size: 2.6rem;">
        Local Producers & Farm Stalls
      </h1>
      <p class="text-muted mx-auto mb-0" style="max-width: 620px; font-size: 1.05rem; line-height: 1.6;">
        Connect directly with passionate growers, multi-generational orchards, and organic homesteads bringing their finest harvests to community markets.
      </p>
    </div>

    {{-- Search & Market Filter Toolbar --}}
    <div class="row justify-content-center mb-5">
      <div class="col-lg-8">
        <form action="{{ route('farmers.index') }}" method="GET" class="p-2 rounded-pill shadow-sm farmer-filter-form" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.08);">
          <div class="d-flex flex-wrap align-items-center farmer-filter-inner">
            <div class="d-flex align-items-center flex-grow-1 ps-3 pe-2">
              <i class="bi bi-search text-muted me-2"></i>
              <input type="text" name="search" class="form-control border-0 shadow-none py-2 px-1 text-dark" placeholder="Search producers, farm names, or specialties..." value="{{ request('search') }}" style="font-size: 0.92rem;">
            </div>
            <div class="d-flex align-items-center ps-2 pe-2 border-start" style="min-width: 180px;">
              <select name="market" class="form-select border-0 shadow-none py-2 text-muted" style="font-size: 0.88rem; cursor: pointer;">
                <option value="">All Market Locations</option>
                @foreach($markets as $m)
                  <option value="{{ $m->id }}" {{ request('market') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn rounded-pill px-4 py-2 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.88rem;">
              Filter Directory
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Producer Grid --}}
    <div class="row g-4">
      @forelse($farmers as $farmer)
        <div class="col-sm-6 col-lg-4 col-xl-3">
          <a href="{{ route('farmers.show', $farmer) }}" class="text-decoration-none">
            <div class="card h-100 border-0 rounded-4 overflow-hidden shadow-sm hover-lift" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.05); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
              {{-- Cover / Avatar Area --}}
              <div class="position-relative overflow-hidden" style="height: 180px; background: #f1f5f9;">
                @if($farmer->banner_image)
                  <img src="{{ str_starts_with($farmer->banner_image, 'http') ? $farmer->banner_image : asset('storage/' . $farmer->banner_image) }}" alt="{{ $farmer->stall_name }}" class="w-100 h-100" style="object-fit: cover;">
                @elseif($farmer->profile_image)
                  <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}" class="w-100 h-100" style="object-fit: cover;">
                @else
                  <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #14532d, #15803d); color: #ffffff; font-size: 3.5rem; font-weight: 800;">
                    {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                  </div>
                @endif

                {{-- Badges on Cover --}}
                <div class="position-absolute top-0 end-0 p-3 d-flex flex-column align-items-end gap-1">
                  @if($farmer->is_organic)
                    <span class="badge rounded-pill px-3 py-1 text-white shadow-sm" style="background: rgba(22, 101, 52, 0.9); font-size: 0.68rem; font-weight: 700; backdrop-filter: blur(4px);">
                      🌿 Certified Organic
                    </span>
                  @endif
                </div>

                {{-- Stall Avatar Floating --}}
                @if($farmer->profile_image && $farmer->banner_image)
                  <div class="position-absolute bottom-0 start-0 ms-3 mb-n3 rounded-circle overflow-hidden shadow" style="width: 52px; height: 52px; border: 3px solid #ffffff; background: #ffffff;">
                    <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
                  </div>
                @endif
              </div>

              {{-- Body Details --}}
              <div class="card-body p-4 d-flex flex-column {{ ($farmer->profile_image && $farmer->banner_image) ? 'pt-4' : '' }}">
                <div class="mb-2">
                  <h5 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 1.1rem; letter-spacing: -0.01em;">
                    {{ $farmer->stall_name }}
                  </h5>
                  <div class="text-muted small" style="font-size: 0.78rem;">
                    Grower: {{ $farmer->contact_person }}
                  </div>
                </div>

                @if($farmer->bio)
                  <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.82rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $farmer->bio }}
                  </p>
                @else
                  <div class="flex-grow-1 mb-3"></div>
                @endif

                {{-- Metadata Row --}}
                <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
                  @if($farmer->average_rating)
                    <div class="d-flex align-items-center gap-1 text-warning small">
                      <i class="bi bi-star-fill"></i>
                      <span class="fw-bold text-dark">{{ number_format($farmer->average_rating, 1) }}</span>
                    </div>
                  @else
                    <span class="badge rounded-pill bg-light text-muted px-2 py-1" style="font-size: 0.68rem;">New Stall</span>
                  @endif

                  <span class="badge rounded-pill px-3 py-1" style="background: rgba(20, 83, 45, 0.08); color: #166534; font-size: 0.74rem; font-weight: 700;">
                    {{ $farmer->active_products_count ?? 0 }} Varieties
                  </span>
                </div>

                @if($farmer->pickup_windows)
                  <div class="mt-2 text-muted small d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                    <i class="bi bi-clock-history text-success"></i>
                    <span class="text-truncate">{{ $farmer->pickup_windows }}</span>
                  </div>
                @endif
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-5">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background: rgba(20, 83, 45, 0.06); font-size: 2.4rem;">
            🌾
          </div>
          <h4 class="fw-bold text-dark mb-2">No Producers Found</h4>
          <p class="text-muted small mx-auto mb-4" style="max-width: 360px;">No farm stalls currently match your search query or location filter.</p>
          <a href="{{ route('farmers.index') }}" class="btn rounded-pill px-4 py-2 fw-semibold text-white" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.88rem;">
            Reset Filter Options
          </a>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-5">
      {{ $farmers->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

    {{-- Register Stall CTA Banner --}}
    <div class="mt-6 p-5 rounded-5 text-center position-relative overflow-hidden shadow-lg" style="background: linear-gradient(135deg, rgba(12, 53, 33, 0.85) 0%, rgba(6, 78, 59, 0.78) 100%), url('{{ asset('images/farmer_cta_bg.jpg') }}') center/cover no-repeat; color: #ffffff;">
      <div class="position-relative z-1 py-4">
        <span class="badge rounded-pill px-4 py-2 mb-3 text-uppercase shadow-sm" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); font-size: 0.75rem; letter-spacing: 0.1em; font-weight: 700;">
          Growers Guild Network
        </span>
        <h2 class="fw-bold mb-3 text-white drop-shadow" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em; font-size: 2.3rem;">
          Are You a Dedicated Regional Producer?
        </h2>
        <p class="text-white mx-auto mb-4 opacity-90" style="max-width: 600px; font-size: 1.05rem; line-height: 1.6; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
          Join MarketLink to receive verified pre-orders directly from conscious community patrons. Simplify stall logistics and eliminate unsold harvest waste.
        </p>
        <a href="{{ route('register.farmer') }}" class="btn rounded-pill px-5 py-3 fw-bold text-dark shadow-lg btn-hover-transform" style="background: #fbbf24; font-size: 0.95rem;">
          <i class="bi bi-shop me-2"></i>Register Your Farm Stall
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
