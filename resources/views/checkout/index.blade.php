@extends('layouts.app')

@section('title', 'Finalize Pre-Order — MarketLink')

@section('content')
<div class="py-5" style="background: radial-gradient(circle at 10% 20%, rgba(20, 83, 45, 0.04) 0%, rgba(248, 250, 252, 0.9) 90%); min-height: 85vh;">
  <div class="container" style="max-width: 1040px;">

    {{-- Breadcrumb Navigation --}}
    <div class="mb-4">
      <a href="{{ route('cart.index') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 hover-text-primary">
        <i class="bi bi-arrow-left"></i>
        <span>Return to Harvest Basket</span>
      </a>
    </div>

    {{-- Page Header --}}
    <div class="mb-5 text-start">
      <div class="badge rounded-pill px-3 py-2 text-uppercase mb-2" style="background: rgba(20, 83, 45, 0.08); color: #14532d; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
        Stall Pickup Confirmation
      </div>
      <h1 class="fw-bold text-dark mb-1" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.02em; font-size: 2.2rem;">
        Confirm Your Pre-Orders
      </h1>
      <p class="text-muted mb-0" style="font-size: 0.95rem;">
        Select your designated market stall & pickup time slot for each producer. Payment is fulfilled in cash upon collection.
      </p>
    </div>

    @if($errors->any())
      <div class="alert border-0 rounded-4 shadow-sm mb-4 p-4" style="background: rgba(220, 38, 38, 0.08); border-left: 4px solid #dc2626 !important; color: #991b1b;">
        <div class="d-flex align-items-center gap-2 fw-bold mb-2">
          <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
          <span>Please address the following requirements:</span>
        </div>
        <ul class="mb-0 ps-3 small">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Per-Farmer Checkout Cards --}}
    @foreach($farmerCheckouts as $farmerId => $fc)
      @php $farmer = $fc['farmer']; @endphp

      <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.06);">
        {{-- Farmer Header Banner --}}
        <div class="p-4" style="background: linear-gradient(135deg, #14532d 0%, #166534 100%); color: #ffffff;">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: rgba(255,255,255,0.15); font-size: 1.4rem;">
                🌾
              </div>
              <div>
                <h4 class="fw-bold mb-0" style="letter-spacing: -0.01em;">{{ $farmer->stall_name }}</h4>
                <div class="small text-white-50 mt-1 d-flex align-items-center gap-2">
                  <span>Producer: {{ $farmer->contact_person }}</span>
                  @if($farmer->phone)
                    <span>&bull;</span>
                    <span><i class="bi bi-telephone me-1"></i>{{ $farmer->phone }}</span>
                  @endif
                </div>
              </div>
            </div>
            <div>
              <span class="badge rounded-pill px-3 py-2 text-uppercase" style="background: rgba(255,255,255,0.2); font-size: 0.72rem; letter-spacing: 0.06em; font-weight: 700;">
                {{ count($fc['items']) }} Item(s)
              </span>
            </div>
          </div>
        </div>

        <div class="card-body p-4 p-md-5">
          {{-- Items Table --}}
          <div class="mb-4">
            <h6 class="fw-bold text-dark text-uppercase mb-3" style="font-size: 0.76rem; letter-spacing: 0.08em; color: #64748b;">
              Reserved Produce Items
            </h6>
            <div class="table-responsive rounded-4 border" style="border-color: #f1f5f9 !important;">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead style="background: #f8fafc;">
                  <tr style="border-bottom: 1px solid #e2e8f0;">
                    <th class="ps-4 py-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 0.05em;">Product</th>
                    <th class="text-center py-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 0.05em;">Quantity</th>
                    <th class="text-end py-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 0.05em;">Price</th>
                    <th class="text-end pe-4 py-3 fw-bold text-muted small text-uppercase" style="letter-spacing: 0.05em;">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($fc['items'] as $item)
                    <tr>
                      <td class="ps-4 py-3">
                        <div class="fw-bold text-dark">{{ $item['name'] }}</div>
                        <div class="text-muted small" style="font-size: 0.78rem;">Unit: {{ $item['unit'] ?? 'item' }}</div>
                      </td>
                      <td class="text-center py-3">
                        <span class="badge rounded-pill px-3 py-1" style="background: #f1f5f9; color: #334155; font-size: 0.82rem; font-weight: 600;">
                          {{ $item['quantity'] }}
                        </span>
                      </td>
                      <td class="text-end py-3 text-muted">
                        PKR {{ number_format($item['price'], 2) }}
                      </td>
                      <td class="text-end pe-4 py-3 fw-bold text-dark">
                        PKR {{ number_format($item['price'] * $item['quantity'], 2) }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot style="background: #fafbfd;">
                  <tr style="border-top: 2px solid #e2e8f0;">
                    <td colspan="3" class="ps-4 py-3 fw-bold text-dark text-end">
                      Stall Subtotal
                    </td>
                    <td class="text-end pe-4 py-3 fw-bold" style="color: #15803d; font-size: 1.15rem;">
                      PKR {{ number_format($fc['subtotal'], 2) }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          {{-- Per-Farmer Order Placement Form --}}
          <form action="{{ route('checkout.place') }}" method="POST" class="farmer-checkout-form p-4 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
            @csrf
            <input type="hidden" name="farmer_id" value="{{ $farmerId }}">

            <div class="row g-4">
              {{-- Market Selection --}}
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small" style="letter-spacing: 0.02em;">
                  Pickup Market Stall Location <span class="text-danger">*</span>
                </label>
                @if($fc['markets']->isEmpty())
                  <div class="alert border-0 rounded-3 p-3 small" style="background: rgba(217, 119, 6, 0.1); color: #92400e;">
                    <i class="bi bi-info-circle me-1"></i> No active markets configured for this producer.
                  </div>
                @else
                  <div class="position-relative">
                    <select name="market_id" class="form-select rounded-3 py-2 px-3 shadow-none border" style="font-size: 0.9rem;" required>
                      <option value="">— Select Pickup Market —</option>
                      @foreach($fc['markets'] as $m)
                        <option value="{{ $m->id }}">{{ $m->name }} @if($m->location)({{ $m->location }})@endif</option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>

              {{-- Pickup Slot Selection --}}
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small" style="letter-spacing: 0.02em;">
                  Designated Time Slot <span class="text-danger">*</span>
                </label>
                @if($fc['pickupSlots']->isEmpty())
                  <div class="alert border-0 rounded-3 p-3 small" style="background: rgba(217, 119, 6, 0.1); color: #92400e;">
                    <i class="bi bi-clock-history me-1"></i> No pickup slots currently open for this grower.
                  </div>
                @else
                  <div class="position-relative">
                    <select name="pickup_slot_id" class="form-select rounded-3 py-2 px-3 shadow-none border" style="font-size: 0.9rem;" required>
                      <option value="">— Select Collection Window —</option>
                      @foreach($fc['pickupSlots'] as $slot)
                        <option value="{{ $slot->id }}">
                          {{ $slot->pickup_date->format('D, M j, Y') }} &bull; {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }} ({{ $slot->remaining_capacity }} left)
                        </option>
                      @endforeach
                    </select>
                  </div>
                @endif
              </div>

              {{-- Pickup Person / Household Sharing --}}
              <div class="col-12">
                <label class="form-label fw-semibold text-dark small">
                  Who will collect this order? <span class="text-muted fw-normal">(Pickup Representative)</span>
                </label>
                <select name="pickup_person" class="form-select rounded-3 py-2 px-3 shadow-none border" style="font-size: 0.9rem;">
                  <option value="self">Myself ({{ auth()->user()->name }})</option>
                  @if(!empty($familyMembers))
                    @foreach($familyMembers as $m)
                      <option value="{{ $m['name'] }} ({{ $m['relationship'] }} - {{ $m['phone'] }})">
                        👤 {{ $m['name'] }} ({{ $m['relationship'] }})
                      </option>
                    @endforeach
                  @endif
                </select>
                <div class="form-text text-muted small" style="font-size: 0.74rem;">
                  You can authorize household family members in your <a href="{{ route('customer.profile') }}" target="_blank" class="text-success text-decoration-none">Profile</a>.
                </div>
              </div>

              {{-- Special Requests / Notes --}}
              <div class="col-12">
                <label class="form-label fw-semibold text-dark small">
                  Special Notes for {{ $farmer->stall_name }} <span class="text-muted fw-normal">(Optional)</span>
                </label>
                <textarea name="notes" rows="2" class="form-control rounded-3 py-2 px-3 shadow-none border" style="font-size: 0.9rem;" placeholder="e.g. Please pick slightly greener avocados, pack separately, etc.">{{ old('notes') }}</textarea>
              </div>

              {{-- Action Button --}}
              <div class="col-12 pt-2 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="text-muted small d-flex align-items-center gap-2">
                  <i class="bi bi-cash-coin text-success fs-5"></i>
                  <span>Pay <strong>PKR {{ number_format($fc['subtotal'], 2) }}</strong> in cash at this stall</span>
                </div>

                <button type="submit" class="btn rounded-pill px-5 py-3 fw-bold text-white shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.95rem;" {{ ($fc['markets']->isEmpty() || $fc['pickupSlots']->isEmpty()) ? 'disabled' : '' }}>
                  <i class="bi bi-check-circle-fill"></i>
                  <span>Place Reservation for {{ $farmer->stall_name }}</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endforeach

    {{-- Grand Total Summary Card --}}
    <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 mb-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid rgba(22, 101, 52, 0.15) !important;">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
        <div>
          <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase" style="background: rgba(22, 101, 52, 0.1); color: #166534; font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
            Combined Basket Value
          </span>
          <h3 class="fw-bold mb-1" style="color: #14532d; font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em;">
            Grand Total (All Stalls)
          </h3>
          <p class="text-muted mb-0 small" style="max-width: 480px; line-height: 1.5;">
            Zero upfront fees or online card charges. Fulfill payment in exact cash at each respective producer's market stall upon harvest collection.
          </p>
        </div>

        <div class="text-md-end">
          <div class="text-muted small text-uppercase" style="letter-spacing: 0.08em; font-weight: 600;">Due Across All Stalls</div>
          <div class="fw-bold" style="color: #15803d; font-size: 2.4rem; letter-spacing: -0.03em;">
            PKR {{ number_format($subtotal, 2) }}
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.farmer-checkout-form').forEach(form => {
  form.addEventListener('submit', function() {
    const btn = this.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Confirming Pre-Order...';
    }
  });
});
</script>
@endpush
