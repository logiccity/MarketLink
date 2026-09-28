<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Platform') — MarketLink | eGreen Basket</title>
  <meta name="description" content="MarketLink Luxury Admin Panel — eGreen Basket Marketplace Platform">

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Bootstrap 5 & Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

  {{-- MarketLink Base CSS + Dedicated Admin Luxury Design System --}}
  <link rel="stylesheet" href="{{ asset('css/marketlink.css') }}?v={{ file_exists(public_path('css/marketlink.css')) ? filemtime(public_path('css/marketlink.css')) : time() }}">
  <link rel="stylesheet" href="{{ asset('css/admin-luxury.css') }}?v={{ file_exists(public_path('css/admin-luxury.css')) ? filemtime(public_path('css/admin-luxury.css')) : time() }}">

  @stack('styles')
</head>
<body class="admin-body portal-body">
<div class="portal-wrapper">

  {{-- Sidebar --}}
  <aside class="portal-sidebar admin-theme" id="admin-sidebar" aria-label="Admin Navigation Sidebar">
    
    {{-- Brand Header --}}
    <div class="sidebar-brand-wrapper">
      <a href="{{ route('admin.dashboard') }}" class="sidebar-brand text-decoration-none d-flex align-items-center">
        <img src="{{ asset('images/logo.png') }}" alt="MarketLink" style="height: 38px; width: auto; max-width: 195px; object-fit: contain; display: block;">
      </a>
      <button type="button" class="btn btn-sm d-lg-none text-white border-0 p-1" id="sidebar-close-btn" aria-label="Close sidebar">
        <i class="bi bi-x-lg fs-5"></i>
      </button>
    </div>

    {{-- Sidebar Navigation Items --}}
    <div class="sidebar-nav-container">
      
      {{-- Overview --}}
      <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Executive Dashboard</span>
      </a>

      {{-- Section: User Management --}}
      <div class="sidebar-section-label">User Management</div>

      <a href="{{ route('admin.farmers.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.farmers.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>
        <span>Growers & Stalls</span>
        @php
          $pendingCount = \App\Models\Farmer::where('approval_status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
          <span class="sidebar-pill-badge ms-auto" style="background: var(--admin-gold); color: #101815;">
            {{ $pendingCount }}
          </span>
        @endif
      </a>

      <a href="{{ route('admin.customers.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Patrons & Customers</span>
      </a>

      {{-- Section: Marketplace Operations --}}
      <div class="sidebar-section-label">Marketplace</div>

      <a href="{{ route('admin.markets.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.markets.*') ? 'active' : '' }}">
        <i class="bi bi-shop-window"></i>
        <span>Market Venues</span>
      </a>

      <a href="{{ route('admin.categories.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>
        <span>Categories</span>
      </a>

      <a href="{{ route('admin.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <i class="bi bi-box-seam"></i>
        <span>Harvest Produce</span>
      </a>

      <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i>
        <span>Pre-Orders</span>
      </a>

      <a href="{{ route('admin.reviews.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star-half"></i>
        <span>Patron Reviews</span>
      </a>

      {{-- Section: Intelligence & Desk --}}
      <div class="sidebar-section-label">Intelligence & Desk</div>

      <a href="{{ route('admin.reports.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i>
        <span>Financial Reports</span>
      </a>

      <a href="{{ route('admin.announcements.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
        <i class="bi bi-megaphone"></i>
        <span>Announcements</span>
      </a>

      <a href="{{ route('admin.contacts.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i>
        <span>Concierge Desk</span>
        @php
          $unreadInquiries = \App\Models\ContactMessage::where('status', 'unread')->count();
        @endphp
        @if($unreadInquiries > 0)
          <span class="sidebar-pill-badge ms-auto" style="background: var(--admin-emerald-bright); color: #FFFFFF;">
            {{ $unreadInquiries }}
          </span>
        @endif
      </a>

      {{-- Section: System --}}
      <div class="sidebar-section-label">System</div>

      <a href="{{ route('admin.notifications.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
        @php
          $unreadNotifs = auth()->user() ? auth()->user()->unreadNotifications()->count() : 0;
        @endphp
        @if($unreadNotifs > 0)
          <span class="sidebar-pill-badge ms-auto bg-danger text-white">
            {{ $unreadNotifs }}
          </span>
        @endif
      </a>

      <a href="{{ route('admin.search') }}" class="sidebar-nav-link {{ request()->routeIs('admin.search') ? 'active' : '' }}">
        <i class="bi bi-search"></i>
        <span>Global Search</span>
      </a>

      <a href="{{ route('admin.config.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
        <i class="bi bi-sliders"></i>
        <span>Platform Configuration</span>
      </a>

    </div>

    {{-- Sidebar Footer: Admin Profile & Logout --}}
    <div class="sidebar-footer">
      <div class="sidebar-admin-card">
        <div class="sidebar-admin-avatar">
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="sidebar-admin-info">
          <div class="sidebar-admin-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
          <div class="sidebar-admin-role">Super Administrator</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-link text-white-50 p-1 text-decoration-none" title="Sign Out">
            <i class="bi bi-box-arrow-right fs-5"></i>
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- Main Portal Area --}}
  <div class="portal-main">

    {{-- Top Navbar --}}
    <header class="portal-header-lux">
      
      {{-- Left: Mobile Toggle & Page Title & Breadcrumb --}}
      <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0 flex-grow-1 overflow-hidden">
        <button type="button" class="btn btn-sm d-lg-none btn-lux-icon flex-shrink-0" id="sidebar-toggle-btn" aria-label="Toggle Navigation">
          <i class="bi bi-list fs-5"></i>
        </button>
        <div class="header-title-block min-w-0 overflow-hidden">
          <h1 class="text-truncate mb-0">@yield('page-title', 'Overview')</h1>
          <div class="header-breadcrumbs">
            <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house me-1"></i>Admin</a>
            <i class="bi bi-chevron-right" style="font-size: 0.6rem;"></i>
            <span>@yield('title', 'Platform Management')</span>
          </div>
        </div>
      </div>

      {{-- Right: Search Trigger, Notifications, View Site, User Menu --}}
      <div class="d-flex align-items-center gap-2 gap-sm-3 flex-shrink-0 ms-2">
        
        {{-- Search Input Trigger --}}
        <a href="{{ route('admin.search') }}" class="header-search-btn d-none d-md-flex" title="Search all admin records">
          <i class="bi bi-search text-success"></i>
          <span>Search records...</span>
          <span class="header-search-kbd">⌘K</span>
        </a>

        {{-- Notifications Bell --}}
        <div class="dropdown">
          <a href="#" class="header-icon-btn text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="System Notifications">
            <i class="bi bi-bell"></i>
            @if($unreadNotifs > 0)
              <span class="header-badge-dot">{{ $unreadNotifs }}</span>
            @endif
          </a>
          <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-3 border-0 mt-2" style="width: 320px; border: 1px solid var(--admin-border) !important;">
            <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
              <span class="fw-bold small text-dark">Notifications</span>
              <a href="{{ route('admin.notifications.index') }}" class="small text-success text-decoration-none fw-semibold">View All</a>
            </div>
            <div class="py-2 text-center text-muted small">
              @if($unreadNotifs > 0)
                <p class="mb-2">You have <strong>{{ $unreadNotifs }}</strong> unread system alert(s).</p>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-lux-primary py-1 px-3">Open Center</a>
              @else
                <i class="bi bi-check2-circle fs-3 text-success d-block mb-1"></i>
                <span>All notifications caught up!</span>
              @endif
            </div>
          </div>
        </div>

        {{-- View Public Store Button --}}
        <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-lux-outline d-none d-sm-inline-flex" title="Open marketplace in new tab">
          <i class="bi bi-box-arrow-up-right me-1 text-success"></i>
          <span>Live Site</span>
        </a>

        {{-- User Profile Pill Dropdown --}}
        <div class="dropdown">
          <div class="header-user-dropdown-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
            <div class="header-user-avatar">
              {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <span class="small fw-bold text-dark d-none d-md-inline">
              {{ Str::limit(auth()->user()->name ?? 'Admin', 14) }}
            </span>
          </div>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-2 border-0 mt-2" style="min-width: 210px; border: 1px solid var(--admin-border) !important;">
            <li class="px-3 py-2 border-bottom mb-1">
              <div class="fw-bold small text-dark">{{ auth()->user()->name }}</div>
              <div class="text-muted" style="font-size: 0.72rem;">{{ auth()->user()->email }}</div>
            </li>
            <li>
              <a class="dropdown-item rounded-3 py-2 small d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 text-success"></i> Dashboard
              </a>
            </li>
            <li>
              <a class="dropdown-item rounded-3 py-2 small d-flex align-items-center gap-2" href="{{ route('admin.reports.index') }}">
                <i class="bi bi-graph-up text-primary"></i> Analytics
              </a>
            </li>
            <li>
              <a class="dropdown-item rounded-3 py-2 small d-flex align-items-center gap-2" href="{{ route('admin.notifications.index') }}">
                <i class="bi bi-bell text-warning"></i> Notifications
              </a>
            </li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item rounded-3 py-2 small text-danger d-flex align-items-center gap-2">
                  <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
              </form>
            </li>
          </ul>
        </div>

      </div>
    </header>

    {{-- Flash Alerts --}}
    @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $type => $cls)
      @if(session($type))
        <div class="alert alert-{{ $cls }} alert-dismissible fade show border-0 rounded-0 mb-0 px-4 py-3 small shadow-sm d-flex align-items-center gap-2" style="background: {{ $cls === 'success' ? '#ECFDF5' : ($cls === 'danger' ? '#FEF2F2' : '#FFFBEB') }}; border-left: 4px solid {{ $cls === 'success' ? '#10B981' : ($cls === 'danger' ? '#EF4444' : '#F59E0B') }} !important; color: #1E293B;">
          <i class="bi bi-{{ $cls === 'danger' ? 'x-circle-fill text-danger' : ($cls === 'success' ? 'check-circle-fill text-success' : 'exclamation-triangle-fill text-warning') }} fs-5"></i>
          <span class="fw-medium">{{ session($type) }}</span>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    @endforeach

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 rounded-0 mb-0 px-4 py-3 small shadow-sm" style="background: #FEF2F2; border-left: 4px solid #EF4444 !important; color: #1E293B;">
        <strong class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-exclamation-octagon-fill text-danger"></i> Please review the following errors:</strong>
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- Page Content Canvas --}}
    <main class="portal-content-lux" role="main">
      @yield('content')
    </main>

    {{-- Luxury Footer --}}
    <footer class="text-center py-3 border-top mt-auto" style="font-size: 0.76rem; color: var(--admin-text-muted); background: #FFFFFF; border-color: var(--admin-border) !important;">
      <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-2 px-4">
        <span>&copy; {{ date('Y') }} <strong>MarketLink</strong> by eGreen Basket &bull; Enterprise Operations</span>
        <span class="text-muted"><i class="bi bi-shield-lock-fill text-success me-1"></i>256-bit Encrypted Management Portal</span>
      </div>
    </footer>

  </div>
</div>

{{-- Sidebar Mobile Overlay --}}
<div id="sidebar-overlay" class="d-none d-lg-none" style="position:fixed;inset:0;background:rgba(7,23,18,0.65);z-index:1029;backdrop-filter:blur(4px);"></div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('js/marketlink.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Mobile Sidebar Toggle
  const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
  const sidebarCloseBtn = document.getElementById('sidebar-close-btn');
  const adminSidebar = document.getElementById('admin-sidebar');
  const sidebarOverlay = document.getElementById('sidebar-overlay');

  function openSidebar() {
    if (adminSidebar) adminSidebar.classList.add('show');
    if (sidebarOverlay) sidebarOverlay.classList.remove('d-none');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    if (adminSidebar) adminSidebar.classList.remove('show');
    if (sidebarOverlay) sidebarOverlay.classList.add('d-none');
    document.body.style.overflow = '';
  }

  if (sidebarToggleBtn) {
    sidebarToggleBtn.addEventListener('click', openSidebar);
  }

  if (sidebarCloseBtn) {
    sidebarCloseBtn.addEventListener('click', closeSidebar);
  }

  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', closeSidebar);
  }

  // Close sidebar on ESC key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && adminSidebar && adminSidebar.classList.contains('show')) {
      closeSidebar();
    }
  });

  // Close mobile sidebar when clicking any navigation link
  if (adminSidebar) {
    adminSidebar.querySelectorAll('.sidebar-nav-link').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.innerWidth < 992) {
          closeSidebar();
        }
      });
    });
  }

  // Keyboard shortcut Ctrl+K or Cmd+K for Search
  document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      window.location.href = "{{ route('admin.search') }}";
    }
  });
});
</script>

@stack('scripts')
</body>
</html>
