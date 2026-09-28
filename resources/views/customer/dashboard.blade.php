@extends('layouts.customer')

@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stats Row --}}
<div class="dashboard-kpi-grid">
  @php
    $customerStats = [
      [
        'icon' => 'bag-check',
        'label' => 'Total Pre-Orders',
        'value' => $stats['total_orders'],
        'prefix' => '',
        'bg' => 'var(--color-success-soft)',
        'color' => 'var(--color-success)',
        'badge' => 'All-Time',
        'accent' => 'linear-gradient(90deg, #3D8B62, #68B988)',
      ],
      [
        'icon' => 'hourglass-split',
        'label' => 'Awaiting Confirmation',
        'value' => $stats['pending_orders'],
        'prefix' => '',
        'bg' => 'var(--color-warning-soft)',
        'color' => 'var(--color-warning)',
        'badge' => 'Pending',
        'accent' => 'linear-gradient(90deg, #D89B3D, #F5BE6B)',
      ],
      [
        'icon' => 'heart',
        'label' => 'Saved Favourites',
        'value' => $stats['favorites'],
        'prefix' => '',
        'bg' => '#FDF2F8',
        'color' => '#c065a0',
        'badge' => 'Saved',
        'accent' => 'linear-gradient(90deg, #c065a0, #f472b6)',
      ],
      [
        'icon' => 'currency-dollar',
        'label' => 'Total Spent at Pickup',
        'value' => number_format($stats['total_spent'], 2),
        'prefix' => 'PKR ',
        'bg' => 'var(--color-info-soft)',
        'color' => 'var(--color-info)',
        'badge' => 'Settled',
        'accent' => 'linear-gradient(90deg, #3B7BBF, #60A5FA)',
      ],
    ];
  @endphp

  @foreach($customerStats as $s)
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

{{-- Welcome Banner --}}
<div class="dash-welcome-banner customer-banner mb-4">
  <div class="dash-welcome-eyebrow">
    <span>✦</span> eGreen Basket
  </div>
  <div class="dash-welcome-title">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}.</div>
  <p class="dash-welcome-sub mb-0">Discover fresh, seasonal produce from trusted local farmers. Reserve your weekly order and pick it up at your convenience.</p>
  <div class="d-flex flex-wrap gap-2 mt-3" style="position: relative; z-index: 2;">
    <a href="{{ route('products.index') }}" class="btn-lux-gold btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;">
      <i class="bi bi-basket2"></i> Browse Produce
    </a>
    <a href="{{ route('markets.index') }}" class="btn d-inline-flex align-items-center gap-2 rounded-pill px-4" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #FFF; font-size: 0.88rem; font-weight: 600;">
      <i class="bi bi-geo-alt"></i> Find Markets
    </a>
  </div>
</div>

<div class="row g-4">
  {{-- Recent Orders --}}
  <div class="col-lg-8">
    <div class="portal-section-head">
      <h2 class="portal-section-title">Recent Pre-Orders</h2>
      <a href="{{ route('customer.orders.index') }}" class="portal-section-link">
        View all <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    @forelse($recentOrders as $order)
      @php
        $statusMap = [
          'PLACED'           => ['cls' => 'badge-order-placed',    'icon' => 'hourglass-split',   'label' => 'Order Placed'],
          'ACCEPTED'         => ['cls' => 'badge-order-accepted',  'icon' => 'check-circle',      'label' => 'Confirmed'],
          'DECLINED'         => ['cls' => 'badge-order-cancelled', 'icon' => 'x-circle',          'label' => 'Declined'],
          'READY_FOR_PICKUP' => ['cls' => 'badge-order-ready',     'icon' => 'gift',              'label' => 'Ready for Pickup'],
          'COMPLETED'        => ['cls' => 'badge-order-completed', 'icon' => 'bag-check',         'label' => 'Completed'],
          'CANCELLED'        => ['cls' => 'badge-order-cancelled', 'icon' => 'x-circle',          'label' => 'Cancelled'],
        ];
        $sm = $statusMap[$order->status] ?? ['cls' => 'badge-order-placed', 'icon' => 'circle', 'label' => $order->status];
      @endphp
      <div class="order-row-lux">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
          <div>
            <div class="d-flex align-items-center gap-2 mb-1">
              <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">#{{ $order->order_number }}</span>
              <span class="badge-order-status {{ $sm['cls'] }}">
                <i class="bi bi-{{ $sm['icon'] }}"></i>{{ $sm['label'] }}
              </span>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap" style="font-size: 0.82rem; color: var(--color-text-muted);">
              <span><i class="bi bi-bag me-1"></i>{{ $order->orderItems->count() }} item(s)</span>
              @if($order->farmer)
                <span><i class="bi bi-shop me-1"></i>{{ $order->farmer->stall_name }}</span>
              @endif
              <span><i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y') }}</span>
            </div>
            @if($order->pickup_date)
              <div class="mt-1" style="font-size: 0.8rem; color: var(--color-success); font-weight: 600;">
                <i class="bi bi-calendar-check me-1"></i>Pickup: {{ $order->pickup_date->format('D, d M Y') }}
              </div>
            @endif
          </div>
          <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 flex-grow-1 flex-sm-grow-0">
            <div class="text-sm-end">
              <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.1rem;">PKR {{ number_format($order->total_amount, 2) }}</div>
              <div style="font-size: 0.75rem; color: var(--color-text-muted);">at pickup</div>
            </div>
            <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-size: 0.8rem; font-weight: 600;">
              View <i class="bi bi-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="portal-card text-center" style="padding: 3.5rem 2rem;">
        <div style="font-size: 3rem; opacity: 0.25; margin-bottom: 1rem;">📦</div>
        <h6 style="font-family: var(--font-serif); color: var(--color-dark); font-size: 1.1rem;">No Pre-Orders Yet</h6>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">Start by browsing fresh local produce and placing your first reservation.</p>
        <a href="{{ route('products.index') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.5rem; font-size: 0.88rem;">
          <i class="bi bi-basket2"></i> Browse Produce
        </a>
      </div>
    @endforelse
  </div>

  {{-- Sidebar --}}
  <div class="col-lg-4 d-flex flex-column gap-4">

    {{-- Unread Notifications --}}
    @if($notifications->count() > 0)
      <div class="portal-card">
        <div class="portal-card-header d-flex align-items-center justify-content-between">
          <h3 class="portal-card-title">
            <i class="bi bi-bell text-gold me-2" style="color: var(--color-accent);"></i>Notifications
          </h3>
          <a href="{{ route('customer.notifications.index') }}" class="portal-section-link" style="font-size: 0.78rem;">All</a>
        </div>
        <div class="portal-card-body">
          @foreach($notifications->take(4) as $notif)
            <div class="portal-list-item d-flex gap-3 align-items-start">
              <div style="width: 8px; height: 8px; border-radius: 50%; margin-top: 6px; flex-shrink: 0; background: {{ $notif->read_at ? 'var(--color-border)' : 'var(--color-accent)' }}; {{ $notif->read_at ? '' : 'box-shadow: 0 0 6px rgba(201,168,106,0.7);' }}"></div>
              <div>
                <div style="font-size: 0.84rem; color: var(--color-text-dark); line-height: 1.35;">{{ $notif->data['message'] ?? 'New notification' }}</div>
                <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-top: 2px;">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- Favourite Products --}}
    <div class="portal-card">
      <div class="portal-card-header d-flex align-items-center justify-content-between">
        <h3 class="portal-card-title">
          <i class="bi bi-heart me-2" style="color: #c065a0;"></i>Saved Favourites
        </h3>
        <a href="{{ route('customer.favorites.index') }}" class="portal-section-link" style="font-size: 0.78rem;">All</a>
      </div>
      <div class="portal-card-body">
        @forelse($favorites as $fav)
          <a href="{{ route('products.show', $fav->product) }}" class="text-decoration-none">
            <div class="portal-list-item d-flex align-items-center gap-3">
              <div style="width: 44px; height: 44px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: var(--color-sage-soft);">
                @if($fav->product->image)
                  <img src="{{ str_starts_with($fav->product->image, 'http') || str_starts_with($fav->product->image, '/') ? $fav->product->image : asset('storage/' . $fav->product->image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🌿</div>
                @endif
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <div style="font-size: 0.86rem; font-weight: 600; color: var(--color-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $fav->product->name }}</div>
                <div style="font-size: 0.76rem; color: var(--color-text-muted);">PKR {{ number_format($fav->product->price, 2) }} / {{ $fav->product->unit }}</div>
              </div>
              <i class="bi bi-heart-fill" style="color: #c065a0; font-size: 0.85rem; flex-shrink: 0;"></i>
            </div>
          </a>
        @empty
          <div class="text-center py-4 text-muted">
            <i class="bi bi-heart fs-3 d-block mb-2 opacity-50"></i>
            <p class="small mb-0">Browse products and tap ❤️ to save favourites here.</p>
          </div>
        @endforelse
      </div>
    </div>

    <

    {{-- Upcoming Pickups --}}
    @if($upcomingPickups->count() > 0)
      <div class="portal-card">
        <div class="portal-card-header d-flex align-items-center justify-content-between">
          <h3 class="portal-card-title">
            <i class="bi bi-calendar-event me-2" style="color: var(--color-secondary);"></i>Upcoming Pickups
          </h3>
          <span class="badge rounded-pill bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem;">{{ $upcomingPickups->count() }}</span>
        </div>
        <div class="portal-card-body">
          @foreach($upcomingPickups as $order)
            <div class="portal-list-item d-flex gap-3 align-items-center">
              <div class="text-center px-2 py-1" style="background: var(--color-sage-soft); border-radius: 10px; min-width: 48px; flex-shrink: 0;">
                <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-secondary); font-size: 1.3rem; line-height: 1;">{{ $order->pickup_date->format('d') }}</div>
                <div style="font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); font-weight: 700;">{{ $order->pickup_date->format('M') }}</div>
              </div>
              <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-dark);">#{{ $order->order_number }}</div>
                <div style="font-size: 0.78rem; color: var(--color-text-muted);">{{ $order->farmer->stall_name ?? 'Farmer' }}</div>
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-success);">PKR {{ number_format($order->total_amount, 2) }} cash</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>

@endsection
