<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'My Account') — eGreen Basket MarketLink</title>
  <meta name="description" content="Customer Account Portal — eGreen Basket MarketLink">

  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/marketlink.css') }}">
  @stack('styles')
</head>
<body class="portal-body customer-portal-body">
<div class="portal-wrapper">

  <!-- Customer Sidebar -->
  <nav class="portal-sidebar customer-theme" id="customer-sidebar">

    <!-- Brand -->
    <div class="d-flex align-items-center justify-content-between p-3">
      <a href="{{ route('customer.dashboard') }}" class="sidebar-brand text-decoration-none d-flex align-items-center m-0 p-0">
        <img src="{{ asset('images/logo.png') }}" alt="MarketLink" style="height: 38px; width: auto; max-width: 195px; object-fit: contain; display: block;">
      </a>
      <button type="button" class="btn btn-sm d-lg-none text-white border-0 p-1" id="customer-sidebar-close-btn" aria-label="Close sidebar">
        <i class="bi bi-x-lg fs-5"></i>
      </button>
    </div>

    <!-- Customer User Block -->
    <div class="sidebar-user-block">
      <div class="sidebar-user-avatar" style="background: linear-gradient(135deg, var(--color-secondary), var(--color-primary));">
        <span style="color: #FFFFFF; font-family: var(--font-serif); font-size: 1rem; font-weight: 700;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </span>
      </div>
      <div class="overflow-hidden">
        <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-user-role">Community Shopper</div>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <a href="{{ route('customer.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> My Dashboard
      </a>

      <div class="sidebar-section-label">Orders & Basket</div>

      <a href="{{ route('customer.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> My Pre-Orders
        @php
          $customer = auth()->user()->customer;
          $activeOrders = $customer ? \App\Models\Order::where('customer_id', $customer->id)->whereIn('status', ['PLACED', 'ACCEPTED', 'READY_FOR_PICKUP'])->count() : 0;
        @endphp
        @if($activeOrders > 0)
          <span class="badge rounded-pill ms-auto" style="background: var(--color-accent); color: var(--color-dark); font-size: 0.68rem;">{{ $activeOrders }}</span>
        @endif
      </a>

      <a href="{{ route('cart.index') }}" class="sidebar-nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
        <i class="bi bi-basket2"></i> My Basket
      </a>

      <a href="{{ route('customer.favorites.index') }}" class="sidebar-nav-link {{ request()->routeIs('customer.favorites.*') ? 'active' : '' }}">
        <i class="bi bi-heart"></i> Favourites
      </a>

      <a href="{{ route('customer.reviews.index') }}" class="sidebar-nav-link {{ request()->routeIs('customer.reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star"></i> My Reviews
        @php
          $pendingReviews = $customer ? \App\Models\Order::where('customer_id', $customer->id)->where('status', 'COMPLETED')->whereDoesntHave('reviews', fn($q) => $q->where('customer_id', $customer->id))->count() : 0;
        @endphp
        @if($pendingReviews > 0)
          <span class="badge rounded-pill ms-auto" style="background: #f59e0b; color: #1a1a1a; font-size: 0.68rem;">{{ $pendingReviews }}</span>
        @endif
      </a>

      <a href="{{ route('customer.notifications.index') }}" class="sidebar-nav-link {{ request()->routeIs('customer.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i> Notifications
        @php $unread = auth()->user()->unreadNotifications->count(); @endphp
        @if($unread > 0)
          <span class="badge rounded-pill ms-auto" style="background: var(--color-danger); color: #FFF; font-size: 0.68rem;">{{ $unread }}</span>
        @endif
      </a>

      <div class="sidebar-section-label">Explore</div>

      <a href="{{ route('customer.search') }}" class="sidebar-nav-link {{ request()->routeIs('customer.search') ? 'active' : '' }}">
        <i class="bi bi-search"></i> Search
      </a>
      <a href="{{ route('products.index') }}" class="sidebar-nav-link">
        <i class="bi bi-shop"></i> Browse Produce
      </a>
      <a href="{{ route('markets.index') }}" class="sidebar-nav-link">
        <i class="bi bi-geo-alt"></i> Find Markets
      </a>
      <a href="{{ route('customer.ai-assistant') }}" class="sidebar-nav-link {{ request()->routeIs('customer.ai-assistant') ? 'active' : '' }}">
        <i class="bi bi-robot"></i> AI Assistant
      </a>

      <div class="sidebar-section-label">Account</div>

      <a href="{{ route('customer.profile') }}" class="sidebar-nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i> Profile & Settings
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
          <h1 class="mb-0">@yield('page-title', 'My Account')</h1>
          <div style="font-size: 0.72rem; color: var(--color-text-muted); font-family: var(--font-sans); margin-top: 1px;">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}
          </div>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-sm rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-1" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-size: 0.82rem; font-weight: 600;">
          <i class="bi bi-basket"></i> Shop Fresh
        </a>
        {{-- Quick Search --}}
        <form action="{{ route('customer.search') }}" method="GET" class="d-none d-md-flex align-items-center" style="position: relative;">
          <div class="input-group input-group-sm" style="width: 200px; border-radius: 100px; overflow: hidden; border: 1px solid var(--color-border);">
            <span class="input-group-text bg-white border-0" style="padding-left: 0.75rem;"><i class="bi bi-search" style="font-size: 0.75rem; color: var(--color-text-muted);"></i></span>
            <input type="text" name="q" class="form-control border-0" style="font-size: 0.8rem; padding: 0 0.5rem;" placeholder="Quick search…" value="{{ request()->routeIs('customer.search') ? request('q') : '' }}">
          </div>
        </form>
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

<!-- AI Assistant Floating Widget -->
<div class="ai-assistant-bubble" style="z-index: 1050;">
  <button type="button" class="ai-bubble-btn" data-bs-toggle="modal" data-bs-target="#aiAssistantModal" title="eGreen Basket AI Market Assistant">
    <span class="robot-icon">🤖</span>
    <span class="ai-live-pulse"></span>
  </button>
</div>

<!-- AI Assistant Modal -->
<div class="modal fade" id="aiAssistantModal" tabindex="-1" aria-labelledby="aiAssistantModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="position: fixed; bottom: 95px; right: 25px; margin: 0; max-width: 400px;">
    <div class="modal-content ai-modal-box">
      <div class="ai-modal-header d-flex align-items-center justify-content-between">
        <div>
          <div class="fw-bold d-flex align-items-center gap-2">
            <span class="fs-5">🤖</span> MarketLink AI Assistant
          </div>
          <div class="small text-white text-opacity-75">Instant answers for produce, markets & orders</div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="ai-chat-messages" id="ai-chat-messages">
        <div class="chat-bubble bot">
          <strong>Hello, {{ explode(' ', auth()->user()->name)[0] }}! 👋</strong><br>
          I'm your eGreen Basket Assistant. I can help you find fresh produce, locate markets, and answer questions about your orders.<br><br>
          <div class="d-flex flex-wrap gap-1 mt-2">
            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('What fresh vegetables are available?')">Fresh Vegetables?</button>
            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('How does market pickup work?')">How does pickup work?</button>
            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('Show me local farmers')">Show local farmers</button>
          </div>
        </div>
      </div>
      <div class="p-3 border-top bg-white">
        <form id="ai-assistant-form" class="d-flex gap-2">
          <input type="text" id="ai-user-prompt" class="form-control form-control-sm rounded-pill" placeholder="Ask about produce, markets, orders..." required maxlength="400">
          <button type="submit" class="btn btn-egreen btn-sm rounded-pill px-3">
            <i class="bi bi-send-fill"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/marketlink.js') }}"></script>
<script>
const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
const customerSidebarCloseBtn = document.getElementById('customer-sidebar-close-btn');
const customerSidebar = document.getElementById('customer-sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
if (sidebarToggleBtn && customerSidebar) {
  sidebarToggleBtn.addEventListener('click', () => {
    const isOpen = customerSidebar.classList.toggle('show');
    sidebarOverlay.style.display = isOpen ? 'block' : 'none';
  });
  customerSidebarCloseBtn?.addEventListener('click', () => {
    customerSidebar.classList.remove('show');
    sidebarOverlay.style.display = 'none';
  });
  sidebarOverlay?.addEventListener('click', () => {
    customerSidebar.classList.remove('show');
    sidebarOverlay.style.display = 'none';
  });
}
</script>
@stack('scripts')
</body>
</html>
