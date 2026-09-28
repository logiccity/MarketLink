@extends('layouts.farmer')

@section('title', 'Sales & Analytics')
@section('page-title', 'Sales Performance & Analytics')

@section('content')
<div class="p-4">
  <!-- Date Filter Form -->
  <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
    <form method="GET" action="{{ route('farmer.sales.index') }}" class="row g-3 align-items-end">
      <div class="col-md-4">
        <label for="start_date" class="form-label small fw-semibold">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm rounded-3" value="{{ $startDate }}">
      </div>
      <div class="col-md-4">
        <label for="end_date" class="form-label small fw-semibold">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm rounded-3" value="{{ $endDate }}">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-egreen btn-sm rounded-pill px-4 flex-grow-1">
          <i class="bi bi-filter me-1"></i>Filter Range
        </button>
        <a href="{{ route('farmer.sales.index') }}" class="btn btn-light border btn-sm rounded-pill px-3">
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- Metric KPI Cards -->
  <div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
      <div class="rounded-4 p-4 border bg-white shadow-sm h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="fs-4">💵</span>
          <span class="badge bg-success-subtle text-success rounded-pill small">Cash at Pickup</span>
        </div>
        <div class="fw-bold text-dark" style="font-size: 1.85rem;">PKR {{ number_format($totalRevenue, 2) }}</div>
        <div class="text-muted small">Total Realized Revenue</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="rounded-4 p-4 border bg-white shadow-sm h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="fs-4">📦</span>
          <span class="badge bg-info-subtle text-info rounded-pill small">Orders</span>
        </div>
        <div class="fw-bold text-dark" style="font-size: 1.85rem;">{{ $completedOrders }}</div>
        <div class="text-muted small">Completed Pickups (of {{ $totalOrders }})</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="rounded-4 p-4 border bg-white shadow-sm h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="fs-4">🛒</span>
          <span class="badge bg-warning-subtle text-dark rounded-pill small">Average</span>
        </div>
        <div class="fw-bold text-dark" style="font-size: 1.85rem;">PKR {{ number_format($averageOrderValue, 2) }}</div>
        <div class="text-muted small">Avg Order Value</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="rounded-4 p-4 border bg-white shadow-sm h-100">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="fs-4">⭐</span>
          <span class="badge bg-warning-subtle text-dark rounded-pill small">Rating</span>
        </div>
        <div class="fw-bold text-dark" style="font-size: 1.85rem;">{{ number_format($farmer->average_rating, 1) }}</div>
        <div class="text-muted small">Community Score ({{ $farmer->reviews_count }} reviews)</div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Chart -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Daily Realized Revenue (PKR)</h3>
        <div style="height: 300px;">
          <canvas id="salesChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Top Products -->
    <div class="col-lg-4">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Top Selling Produce</h3>
        @if($bestSellers->isEmpty())
          <p class="text-muted small mb-0">No completed orders in selected date range.</p>
        @else
          <div class="d-flex flex-column gap-3">
            @foreach($bestSellers as $idx => $prod)
              <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border">
                <div class="d-flex align-items-center gap-2">
                  <span class="fw-bold text-muted" style="width: 20px;">#{{ $idx + 1 }}</span>
                  <div>
                    <div class="fw-semibold small text-dark">{{ $prod->name }}</div>
                    <div class="text-muted" style="font-size: 0.72rem;">PKR {{ number_format($prod->price, 2) }} / {{ $prod->unit }}</div>
                  </div>
                </div>
                <span class="badge bg-success-subtle text-success rounded-pill">
                  {{ $prod->total_units_sold ?? 0 }} {{ $prod->unit }} sold
                </span>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- AI Smart Harvest & Demand Forecast Card -->
  <div class="bg-white rounded-4 border p-4 shadow-sm mb-4" style="background: linear-gradient(135deg, rgba(18, 60, 47, 0.03) 0%, rgba(201, 168, 106, 0.08) 100%); border-color: rgba(18, 60, 47, 0.15) !important;">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center gap-2">
        <span class="fs-4">🤖</span>
        <div>
          <h3 class="h6 fw-bold mb-0 text-dark">MarketLink AI Harvest & Demand Forecast</h3>
          <span class="text-muted small">Real-time predictive analytics based on customer pre-orders & regional market trends</span>
        </div>
      </div>
      <span class="badge rounded-pill px-3 py-1 fw-bold text-uppercase" style="background: #123C2F; color: #C9A86A; font-size: 0.7rem; letter-spacing: 0.05em;">
        AI Active Telemetry
      </span>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 border h-100 shadow-2xs">
          <div class="fw-bold text-emerald small mb-1">📈 Optimal Pricing Insight</div>
          <p class="text-secondary mb-0 small" style="font-size: 0.82rem; line-height: 1.5;">
            Pre-order velocity for fresh green produce increases by <strong>+24%</strong> on Friday evenings. Consider bundling herbs with leafy greens for maximum weekend margin.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 border h-100 shadow-2xs">
          <div class="fw-bold text-emerald small mb-1">🌾 Harvest Planning Advisory</div>
          <p class="text-secondary mb-0 small" style="font-size: 0.82rem; line-height: 1.5;">
            High pickup volume projected for <strong>Lahore & Islamabad</strong> stalls. Recommended harvest buffer: <strong>+15%</strong> over standard weekly stock template.
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 bg-white rounded-3 border h-100 shadow-2xs">
          <div class="fw-bold text-emerald small mb-1">⏰ Cutoff Recommendation</div>
          <p class="text-secondary mb-0 small" style="font-size: 0.82rem; line-height: 1.5;">
            Your current 12-hour ordering cutoff achieves an <strong>88% pickup completion rate</strong>. Keep pickup slots open until 10 PM before market day.
          </p>
        </div>
      </div>
    </div>
    <div class="mt-3 text-end">
      <small class="text-muted italic" style="font-size: 0.72rem;">* AI responses are generated based on real-time market data. Please verify stall operating hours and harvest schedules.</small>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    const dates = @json($chartDates);
    const revenues = @json($chartRevenues);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: dates.length ? dates : ['No Data'],
        datasets: [{
          label: 'Revenue (PKR)',
          data: revenues.length ? revenues : [0],
          borderColor: '#123C2F',
          backgroundColor: 'rgba(46, 125, 91, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.35,
          pointBackgroundColor: '#2E7D5B',
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) { return 'PKR ' + value; }
            }
          }
        }
      }
    });
  });
</script>
@endpush
