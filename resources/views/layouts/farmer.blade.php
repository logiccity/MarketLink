<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Farmer Portal') — eGreen Basket MarketLink</title>
  <meta name="description" content="Farmer Stall Portal — eGreen Basket MarketLink">

  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="{{ asset('css/marketlink.css') }}">
  @stack('styles')
</head>
<body class="portal-body farmer-portal-body">
<div class="portal-wrapper">

  @php $farmer = auth()->user()->farmer; @endphp

  <!-- Farmer Sidebar -->
  <nav class="portal-sidebar farmer-theme" id="farmer-sidebar">

    <!-- Brand -->
    <div class="d-flex align-items-center justify-content-between p-3">
      <a href="{{ route('farmer.dashboard') }}" class="sidebar-brand text-decoration-none d-flex align-items-center m-0 p-0">
        <img src="{{ asset('images/logo.png') }}" alt="MarketLink" style="height: 38px; width: auto; max-width: 195px; object-fit: contain; display: block;">
      </a>
      <button type="button" class="btn btn-sm d-lg-none text-white border-0 p-1" id="farmer-sidebar-close-btn" aria-label="Close sidebar">
        <i class="bi bi-x-lg fs-5"></i>
      </button>
    </div>

    <!-- Farmer Identity Block -->
    <div class="sidebar-user-block">
      <div class="sidebar-user-avatar" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));">
        @if($farmer && $farmer->profile_image)
          <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}">
        @else
          <span style="color: #FFFFFF; font-family: var(--font-serif); font-size: 1rem; font-weight: 700;">
            {{ strtoupper(substr($farmer ? $farmer->stall_name : auth()->user()->name, 0, 1)) }}
          </span>
        @endif
      </div>
      <div class="overflow-hidden">
        <div class="sidebar-user-name">{{ $farmer ? $farmer->stall_name : auth()->user()->name }}</div>
        <div class="d-flex align-items-center gap-1 mt-1">
          @if($farmer && $farmer->isApproved())
            <span style="font-size: 0.62rem; background: rgba(61,139,98,0.3); color: #A8EDBC; border: 1px solid rgba(168,237,188,0.3); border-radius: 100px; padding: 2px 8px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;">✓ Approved</span>
          @elseif($farmer && $farmer->isPending())
            <span style="font-size: 0.62rem; background: rgba(216,155,61,0.3); color: #F5D48B; border: 1px solid rgba(245,212,139,0.3); border-radius: 100px; padding: 2px 8px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;">⏳ Pending</span>
          @else
            <span style="font-size: 0.62rem; background: rgba(198,91,91,0.3); color: #F5AAAA; border: 1px solid rgba(245,170,170,0.3); border-radius: 100px; padding: 2px 8px; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;">Suspended</span>
          @endif
        </div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <a href="{{ route('farmer.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>

      <div class="sidebar-section-label">Produce & Stock</div>

      <a href="{{ route('farmer.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i> Products
      </a>
      <a href="{{ route('farmer.weekly-stock.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.weekly-stock.*') ? 'active' : '' }}">
        <i class="bi bi-calendar3-week"></i> Weekly Stock
      </a>
      <a href="{{ route('farmer.pickup-slots.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.pickup-slots.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i> Pickup Slots
      </a>

      <div class="sidebar-section-label">Orders & Business</div>

      <a href="{{ route('farmer.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Pre-Orders
        @php
          $pendingOrders = $farmer ? \App\Models\Order::where('farmer_id', $farmer->id)->where('status', 'PLACED')->count() : 0;
        @endphp
        @if($pendingOrders > 0)
          <span class="badge rounded-pill ms-auto" style="background: var(--color-accent); color: var(--color-dark); font-size: 0.68rem;">{{ $pendingOrders }}</span>
        @endif
      </a>
      <a href="{{ route('farmer.markets.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.markets.*') ? 'active' : '' }}">
        <i class="bi bi-shop"></i> My Markets
      </a>
      <a href="{{ route('farmer.reviews.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star"></i> Reviews
      </a>
      <a href="{{ route('farmer.sales.index') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.sales.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i> Sales & Analytics
      </a>

      <div class="sidebar-section-label">Account</div>

      <a href="{{ route('farmer.profile') }}" class="sidebar-nav-link {{ request()->routeIs('farmer.profile') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i> Stall Profile
      </a>
    </nav>

    <!-- Sign Out -->
    <div class="sidebar-signout">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm w-100 rounded-pill py-2" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.75); font-size: 0.84rem; font-weight: 500;">
          <i class="bi bi-box-arrow-right me-2"></i>Sign Out
        </button>
      </form>
    </div>
  </nav>

  <!-- Main Area -->
  <div class="portal-main">

    <!-- Topbar -->
    <header class="portal-header">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-sm d-lg-none border-0 p-1" id="sidebar-toggle-btn" style="background: var(--color-bg); color: var(--color-dark);">
          <i class="bi bi-list fs-4"></i>
        </button>
        <div>
          <h1 class="mb-0">@yield('page-title', 'Farm Dashboard')</h1>
          <div style="font-size: 0.72rem; color: var(--color-text-muted); font-family: var(--font-sans); margin-top: 1px;">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        @if($farmer)
          <span class="d-none d-md-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill" style="background: var(--color-sage-soft); color: var(--color-secondary); font-size: 0.78rem; font-weight: 700; border: 1px solid rgba(168,201,160,0.3);">
            <i class="bi bi-shop"></i> {{ Str::limit($farmer->stall_name, 22) }}
          </span>
        @endif
        <a href="{{ route('home') }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.82rem; font-weight: 500;">
          <i class="bi bi-house me-1"></i> Home
        </a>
      </div>
    </header>

    <!-- Flash Messages -->
    @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $type => $cls)
      @if(session($type))
        <div class="alert alert-{{ $cls }} alert-dismissible fade show border-0 rounded-0 mb-0 px-4 py-2 small">
          {{ session($type) }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    @endforeach

    <!-- Page Content -->
    <main class="portal-content">
      @yield('content')
    </main>
  </div>
</div>

<!-- Sidebar Overlay (Mobile) -->
<div id="sidebar-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1019;"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('js/marketlink.js') }}"></script>
<script>
const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
const farmerSidebarCloseBtn = document.getElementById('farmer-sidebar-close-btn');
const farmerSidebar = document.getElementById('farmer-sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
if (sidebarToggleBtn && farmerSidebar) {
  sidebarToggleBtn.addEventListener('click', () => {
    const isOpen = farmerSidebar.classList.toggle('show');
    sidebarOverlay.style.display = isOpen ? 'block' : 'none';
  });
  farmerSidebarCloseBtn?.addEventListener('click', () => {
    farmerSidebar.classList.remove('show');
    sidebarOverlay.style.display = 'none';
  });
  sidebarOverlay?.addEventListener('click', () => {
    farmerSidebar.classList.remove('show');
    sidebarOverlay.style.display = 'none';
  });
}
</script>
@stack('scripts')
</body>
</html>
