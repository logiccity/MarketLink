@extends('layouts.farmer')

@section('title', 'My Markets')
@section('page-title', 'My Market Stall Locations')

@section('content')
<div class="p-4">
  <div class="row g-4">
    <!-- Active Market Stalls -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h2 class="h5 fw-bold mb-1">Your Associated Markets</h2>
        <p class="text-muted small mb-4">Markets where your stall is located and where customers can collect pre-orders.</p>

        @if($associatedMarkets->isEmpty())
          <div class="text-center py-5 text-muted">
            <span style="font-size: 2.5rem;">📍</span>
            <p class="mt-2 mb-0">You are not associated with any market yet. Select an available market from the right.</p>
          </div>
        @else
          <div class="d-flex flex-column gap-3">
            @foreach($associatedMarkets as $market)
              <div class="d-flex flex-wrap align-items-center justify-content-between p-3 rounded-3 border bg-light">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-success text-white rounded-3 p-3 text-center" style="min-width: 50px;">
                    <i class="bi bi-shop-window fs-4"></i>
                  </div>
                  <div>
                    <h3 class="h6 fw-bold mb-1">{{ $market->name }}</h3>
                    <div class="text-muted small mb-1">
                      <i class="bi bi-geo-alt me-1 text-success"></i>{{ $market->location }}
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                      <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.72rem;">
                        {{ implode(', ', $market->operating_days ?? []) }} ({{ $market->opening_time }} - {{ $market->closing_time }})
                      </span>
                      @if($market->pivot->stall_identifier)
                        <span class="badge bg-secondary-subtle text-dark rounded-pill" style="font-size: 0.72rem;">
                          Stall #{{ $market->pivot->stall_identifier }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                  <a href="{{ route('markets.show', $market) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-box-arrow-up-right me-1"></i>View Market
                  </a>
                  <form action="{{ route('farmer.markets.detach', $market) }}" method="POST" onsubmit="return confirm('Remove stall association from this market?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                      <i class="bi bi-x-circle me-1"></i>Leave Market
                    </button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Join Available Market -->
    <div class="col-lg-4">
      <div class="bg-white rounded-4 border p-4 shadow-sm">
        <h2 class="h5 fw-bold mb-1">Join Another Market</h2>
        <p class="text-muted small mb-4">Expand your pickup locations to reach more local customers.</p>

        @if($availableMarkets->isEmpty())
          <p class="text-muted small mb-0">You are already registered with all active community markets in your area.</p>
        @else
          <form action="{{ route('farmer.markets.attach') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="market_id" class="form-label fw-semibold small">Select Market</label>
              <select name="market_id" id="market_id" class="form-select rounded-3" required>
                <option value="">-- Choose a Community Market --</option>
                @foreach($availableMarkets as $am)
                  <option value="{{ $am->id }}">{{ $am->name }} ({{ $am->city ?? 'Central' }})</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <label for="stall_identifier" class="form-label fw-semibold small">Your Stall Identifier / Number (Optional)</label>
              <input type="text" name="stall_identifier" id="stall_identifier" class="form-control rounded-3" placeholder="e.g. Stall A-14, East Pavilion">
            </div>

            <button type="submit" class="btn btn-egreen rounded-pill w-100 py-2 fw-semibold">
              <i class="bi bi-plus-circle me-1"></i>Associate Stall
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
