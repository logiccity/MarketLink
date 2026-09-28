@extends('layouts.farmer')

@section('title', 'Farm Dashboard')
@section('page-title', 'Farm Dashboard')

@section('content')

@php $farmer = auth()->user()->farmer; @endphp

{{-- Status Alerts --}}
@if($farmer && $farmer->isPending())
  <div class="portal-alert-pending d-flex gap-3 align-items-start">
    <div style="font-size: 2rem; flex-shrink: 0; line-height: 1;">⏳</div>
    <div>
      <div style="font-weight: 700; color: #7A4F00; font-family: var(--font-serif); font-size: 1.05rem; margin-bottom: 0.3rem;">Account Pending Approval</div>
      <p style="font-size: 0.87rem; color: #925D07; margin: 0 0 0.75rem;">Your farm stall registration is under review by the eGreen Basket team. You'll receive an email notification once approved. In the meantime, complete your stall profile.</p>
      <a href="{{ route('farmer.profile') }}" class="btn btn-sm rounded-pill px-4" style="background: var(--color-warning); color: #1A1A1A; font-weight: 700; font-size: 0.82rem; border: none;">Complete Profile</a>
    </div>
  </div>
@endif

@if($farmer && $farmer->isSuspended())
  <div class="portal-alert-danger d-flex gap-3 align-items-center">
    <i class="bi bi-ban" style="font-size: 1.5rem; color: var(--color-danger);"></i>
    <div>
      <div style="font-weight: 700; color: var(--color-danger); font-size: 0.95rem;">Account Suspended</div>
      <div style="font-size: 0.84rem; color: #a04444;">Please contact the admin team at <a href="mailto:support@egreenbasket.com" style="color: var(--color-danger);">support@egreenbasket.com</a> for assistance.</div>
    </div>
  </div>
@endif

{{-- Stats Row --}}
<div class="dashboard-kpi-grid">
  @php
    $farmerStats = [
      ['icon' => 'receipt',         'label' => 'New Orders',      'value' => $stats['new_orders'],      'prefix' => '', 'bg' => 'var(--color-info-soft)',    'color' => 'var(--color-info)',    'badge' => 'This Week', 'accent' => 'linear-gradient(90deg, #3B7BBF, #60A5FA)'],
      ['icon' => 'box-seam',        'label' => 'Listed Products', 'value' => $stats['active_products'], 'prefix' => '', 'bg' => 'var(--color-success-soft)', 'color' => 'var(--color-success)', 'badge' => 'Active',     'accent' => 'linear-gradient(90deg, #3D8B62, #68B988)'],
      ['icon' => 'currency-dollar', 'label' => 'Monthly Revenue', 'value' => number_format($stats['monthly_revenue'], 0), 'prefix' => 'PKR ', 'bg' => 'var(--color-warning-soft)', 'color' => 'var(--color-warning)', 'badge' => 'Month', 'accent' => 'linear-gradient(90deg, #D89B3D, #F5BE6B)'],
      ['icon' => 'star',            'label' => 'Customer Rating', 'value' => $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) . ' ★' : '—', 'prefix' => '', 'bg' => '#FEF9E7', 'color' => '#D4AA00', 'badge' => 'Avg', 'accent' => 'linear-gradient(90deg, #D4AA00, #F5D052)'],
    ];
  @endphp

  @foreach($farmerStats as $s)
    <div class="stat-card-lux" style="--stat-card-accent: {{ $s['accent'] }};">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="stat-card-icon-wrap" style="background: {{ $s['bg'] }}; margin-bottom: 0;">
          <i class="bi bi-{{ $s['icon'] }}" style="color: {{ $s['color'] }}; font-size: 1.15rem;"></i>
        </div>
        <span style="font-size: 0.68rem; font-weight: 700; padding: 0.22rem 0.65rem; border-radius: 100px; background: {{ $s['bg'] }}; color: {{ $s['color'] }}; letter-spacing: 0.04em; text-transform: uppercase;">{{ $s['badge'] }}</span>
      </div>
      <div class="stat-card-num">
        @if(!empty($s['prefix']))
          <span class="stat-card-currency">{{ $s['prefix'] }}</span>
        @endif
        {{ $s['value'] }}
      </div>
      <div class="stat-card-label">{{ $s['label'] }}</div>
    </div>
  @endforeach
</div>

<div class="row g-4">
  {{-- Left: Revenue Chart + Incoming Orders --}}
  <div class="col-lg-8">

    {{-- Revenue Chart --}}
    @if($farmer && $farmer->isApproved())
      <div class="portal-card mb-4">
        <div class="portal-card-header d-flex align-items-center justify-content-between">
          <h3 class="portal-card-title">
            <i class="bi bi-graph-up me-2" style="color: var(--color-secondary);"></i>Revenue — Last 8 Weeks
          </h3>
          <a href="{{ route('farmer.sales.index') }}" class="portal-section-link" style="font-size: 0.78rem;">Full Report</a>
        </div>
        <div class="portal-card-body">
          <canvas id="revenueChart" height="90"></canvas>
        </div>
      </div>
    @endif

    {{-- Incoming Orders --}}
    <div class="portal-section-head">
      <h2 class="portal-section-title">Incoming Orders</h2>
      <a href="{{ route('farmer.orders.index') }}" class="portal-section-link">
        Manage all <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    @forelse($pendingOrders as $order)
      <div class="order-row-lux">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">#{{ $order->order_number }}</span>
              <span class="badge-order-status badge-order-placed">
                <i class="bi bi-hourglass-split"></i>Awaiting Confirmation
              </span>
            </div>
            <div style="font-size: 0.82rem; color: var(--color-text-muted); margin-bottom: 0.6rem;">
              <i class="bi bi-person me-1"></i>
              <strong style="color: var(--color-text-dark);">{{ $order->customer->user->name ?? 'N/A' }}</strong>
              <span class="mx-2">·</span>
              <i class="bi bi-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
            </div>
            <div style="background: var(--color-bg); border-radius: 8px; padding: 0.6rem 0.85rem;">
              @foreach($order->orderItems as $item)
                <div style="font-size: 0.83rem; color: var(--color-text); padding: 0.2rem 0;">
                  <span style="color: var(--color-text-muted);">•</span>
                  <strong>{{ $item->product_name }}</strong> × {{ $item->quantity }}
                  <span style="color: var(--color-text-muted); float: right;">PKR {{ number_format($item->subtotal, 2) }}</span>
                </div>
              @endforeach
            </div>
          </div>
          <div class="d-flex flex-column gap-2 align-items-end flex-shrink-0">
            <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 1.2rem;">PKR {{ number_format($order->total_amount, 2) }}</div>
            <div class="d-flex gap-2">
              <form action="{{ route('farmer.orders.confirm', $order) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm rounded-pill px-3" style="background: var(--color-success); color: #FFF; font-weight: 600; font-size: 0.8rem; border: none;">
                  <i class="bi bi-check-circle me-1"></i>Confirm
                </button>
              </form>
              <a href="{{ route('farmer.orders.show', $order) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.8rem; font-weight: 500;">
                Details
              </a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="portal-card text-center" style="padding: 3rem 2rem;">
        <div style="font-size: 2.5rem; opacity: 0.25; margin-bottom: 0.75rem;">✅</div>
        <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">No pending orders to review right now.</p>
      </div>
    @endforelse
  </div>

  {{-- Right Sidebar --}}
  <div class="col-lg-4 d-flex flex-column gap-4">

    {{-- Stall Profile Completion --}}
    <div class="portal-card">
      <div class="portal-card-header d-flex align-items-center justify-content-between">
        <h3 class="portal-card-title">
          <i class="bi bi-person-gear me-2" style="color: var(--color-accent);"></i>Stall Completion
        </h3>
        @php
          $completion = 0;
          $completionItems = [
            [$farmer?->profile_image, 'Profile photo'],
            [$farmer?->bio, 'Bio description'],
            [$farmer?->phone, 'Phone number'],
            [$farmer?->markets()->count() > 0, 'Market assigned'],
            [$farmer?->activeProducts()->count() > 0, 'Products listed'],
          ];
          foreach($completionItems as $item) if($item[0]) $completion += 20;
        @endphp
        <span style="font-family: var(--font-serif); font-weight: 700; font-size: 1rem; color: var(--color-secondary);">{{ $completion }}%</span>
      </div>
      <div class="portal-card-body">
        <div class="completion-bar-wrap mb-3">
          <div class="completion-bar-fill" style="width: {{ $completion }}%;"></div>
        </div>
        @foreach($completionItems as [$done, $label])
          <div class="portal-list-item d-flex align-items-center gap-2" style="font-size: 0.83rem; color: {{ $done ? 'var(--color-success)' : 'var(--color-text-muted)' }};">
            <i class="bi bi-{{ $done ? 'check-circle-fill' : 'circle' }}" style="font-size: 0.85rem;"></i>
            {{ $label }}
          </div>
        @endforeach
        <a href="{{ route('farmer.profile') }}" class="btn btn-sm w-100 rounded-pill mt-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.82rem;">
          Edit Profile
        </a>
      </div>
    </div>

    {{-- Low Stock Alert --}}
    @if($lowStockProducts->count() > 0)
      <div class="portal-card" style="border-left: 3.5px solid var(--color-warning) !important;">
        <div class="portal-card-header d-flex align-items-center justify-content-between" style="background: var(--color-warning-soft);">
          <h3 class="portal-card-title" style="color: #7A4F00;">
            <i class="bi bi-exclamation-triangle me-2" style="color: var(--color-warning);"></i>Low Stock Alert
          </h3>
          <span class="badge rounded-pill" style="background: var(--color-warning-soft); color: #925D07; border: 1px solid rgba(216,155,61,0.3); font-size: 0.7rem; font-weight: 700;">{{ $lowStockProducts->count() }} items</span>
        </div>
        <div class="portal-card-body">
          @foreach($lowStockProducts as $product)
            <div class="portal-list-item d-flex justify-content-between align-items-center">
              <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-dark);">{{ $product->name }}</span>
              <span style="font-size: 0.75rem; font-weight: 700; background: var(--color-warning-soft); color: #925D07; border: 1px solid rgba(216,155,61,0.3); border-radius: 100px; padding: 0.2rem 0.7rem;">
                {{ $product->stock_quantity }} left
              </span>
            </div>
          @endforeach
          <a href="{{ route('farmer.weekly-stock.index') }}" class="btn btn-sm w-100 rounded-pill mt-3" style="background: var(--color-warning); color: #1A1A1A; font-weight: 700; font-size: 0.82rem; border: none;">
            <i class="bi bi-pencil me-1"></i>Update Stock
          </a>
        </div>
      </div>
    @endif

    {{-- Quick Actions --}}
    <div class="portal-card">
      <div class="portal-card-header">
        <h3 class="portal-card-title">
          <i class="bi bi-lightning me-2" style="color: var(--color-accent);"></i>Quick Actions
        </h3>
      </div>
      <div class="portal-card-body">
        <div class="d-grid gap-2">
          @if($farmer && $farmer->isApproved())
            <a href="{{ route('farmer.products.create') }}" class="btn btn-sm rounded-pill py-2" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.84rem; border: none;">
              <i class="bi bi-plus-circle me-2"></i>Add New Product
            </a>
            <a href="{{ route('farmer.weekly-stock.index') }}" class="btn btn-sm rounded-pill py-2" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.84rem;">
              <i class="bi bi-calendar-week me-2"></i>Set Weekly Stock
            </a>
            <a href="{{ route('farmer.pickup-slots.index') }}" class="btn btn-sm rounded-pill py-2" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.84rem;">
              <i class="bi bi-clock me-2"></i>Manage Pickup Slots
            </a>
          @endif
          <a href="{{ route('farmer.profile') }}" class="btn btn-sm rounded-pill py-2" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-weight: 500; font-size: 0.84rem;">
            <i class="bi bi-person-circle me-2"></i>Edit Stall Profile
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if($farmer && $farmer->isApproved())
const ctx = document.getElementById('revenueChart');
if (ctx) {
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartData['labels'] ?? []) !!},
      datasets: [{
        label: 'Revenue (PKR)',
        data: {!! json_encode($chartData['values'] ?? []) !!},
        backgroundColor: 'rgba(18, 60, 47, 0.12)',
        borderColor: '#1F5A43',
        borderWidth: 2,
        borderRadius: 8,
        borderSkipped: false,
        hoverBackgroundColor: 'rgba(201, 168, 106, 0.15)',
        hoverBorderColor: '#C9A86A',
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: ctx => ' PKR ' + ctx.raw.toLocaleString()
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { callback: v => 'PKR ' + v.toLocaleString(), font: { family: 'Plus Jakarta Sans', size: 11 } },
          grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } }
      }
    }
  });
}
@endif
</script>
@endpush
