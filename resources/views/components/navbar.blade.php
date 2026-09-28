<nav class="ml-ref-navbar navbar navbar-expand-lg" id="main-navbar" aria-label="MarketLink Main Navigation">
  <div class="container-xl d-flex align-items-center justify-content-between">

    <!-- Brand Monogram & Wordmark -->
    <a class="navbar-brand d-inline-flex align-items-center text-decoration-none my-0 py-0 flex-shrink-0" href="{{ route('home') }}">
      <img src="{{ asset('images/logo.png') }}" alt="MarketLink eGreen Basket Logo" class="ref-navbar-logo" style="height: 68px; width: auto; object-fit: contain;">
    </a>

    <!-- Mobile Actions & Toggler (Visible on phones & tablets) -->
    <div class="d-flex align-items-center gap-2 d-lg-none">
      <a href="{{ route('products.index') }}" class="btn btn-sm text-white p-1" title="Search produce and markets">
        <i class="bi bi-search fs-5"></i>
      </a>

      @php $mobileCartCount = count(session('cart', [])); @endphp
      <a href="{{ route('cart.index') }}" class="btn btn-sm text-white p-1 position-relative" title="My Basket">
        <i class="bi bi-basket2 fs-5"></i>
        @if($mobileCartCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 0.65rem; padding: 2px 6px;">
            {{ $mobileCartCount }}
          </span>
        @endif
      </a>

      <button class="navbar-toggler border-0 text-white p-1 my-auto shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-2 text-white"></i>
      </button>
    </div>

    <!-- Nav Links (Center) -->
    <div class="collapse navbar-collapse justify-content-center" id="mainNav">
      <ul class="navbar-nav align-items-lg-center gap-lg-3 gap-xl-4 my-3 my-lg-0 text-center">
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
            Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('markets.*') ? 'active' : '' }}" href="{{ route('markets.index') }}">
            Markets
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('farmers.*') ? 'active' : '' }}" href="{{ route('farmers.index') }}">
            Farmers
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
            Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
            About
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link ref-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
            Contact
          </a>
        </li>
      </ul>

      <!-- Right Action Group in Mobile Menu -->
      <div class="d-lg-none d-flex flex-column gap-2 pt-3 border-top border-white border-opacity-10 text-start">
        @auth
          <div class="d-flex align-items-center gap-2 p-2 rounded-3 mb-1" style="background: rgba(255,255,255,0.08);">
            <div class="ref-user-avatar">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
              <div class="text-white small fw-bold">{{ auth()->user()->name }}</div>
              <div class="text-white-50 text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">{{ auth()->user()->role }}</div>
            </div>
          </div>

          @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-warning w-100 rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 mb-1">
              <i class="bi bi-speedometer2"></i> Admin Dashboard
            </a>
          @elseif(auth()->user()->isFarmer())
            <a href="{{ route('farmer.dashboard') }}" class="btn btn-sm btn-outline-warning w-100 rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 mb-1">
              <i class="bi bi-grid"></i> Farmer Portal
            </a>
          @else
            <a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-outline-warning w-100 rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 mb-1">
              <i class="bi bi-speedometer2"></i> My Dashboard
            </a>
            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-outline-success text-white w-100 rounded-pill d-flex align-items-center justify-content-center gap-2 py-2 mb-1">
              <i class="bi bi-basket2"></i> My Basket ({{ $mobileCartCount }})
            </a>
          @endif

          <form action="{{ route('logout') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-pill py-2">
              <i class="bi bi-box-arrow-right me-1"></i> Sign Out
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn ref-btn-login w-100">Login</a>
          <a href="{{ route('register') }}" class="btn ref-btn-signup w-100">Sign Up</a>
        @endauth
      </div>
    </div>

    <!-- Right Action Group (Desktop) -->
    <div class="d-none d-lg-flex align-items-center gap-2 gap-xl-3 flex-shrink-0">
      <!-- Search Icon -->
      <a href="{{ route('products.index') }}" class="ref-search-btn" title="Search produce and markets">
        <i class="bi bi-search"></i>
      </a>

      @auth
        @if(auth()->user()->isCustomer())
          @php $cartCount = count(session('cart', [])); @endphp
          <a href="{{ route('cart.index') }}" class="ref-basket-badge text-decoration-none position-relative" title="My Basket">
            <i class="bi bi-basket2 text-white fs-5"></i>
            @if($cartCount > 0)
              <span class="ref-badge-count">{{ $cartCount }}</span>
            @endif
          </a>
        @endif

        <!-- User Dropdown -->
        <div class="dropdown">
          <button class="ref-user-pill btn border-0 dropdown-toggle d-inline-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="ref-user-avatar">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="text-white small fw-semibold">
              {{ Str::limit(auth()->user()->name, 12) }}
            </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2" style="background: #143526; min-width: 200px;">
            <li class="px-3 py-2 border-bottom border-white border-opacity-10">
              <div class="fw-bold text-white small">{{ auth()->user()->name }}</div>
              <div class="text-white-50" style="font-size: 0.72rem; text-transform: uppercase;">
                {{ auth()->user()->role }}
              </div>
            </li>
            @if(auth()->user()->isAdmin())
              <li><a class="dropdown-item text-white py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 text-warning me-2"></i>Admin Dashboard</a></li>
            @elseif(auth()->user()->isFarmer())
              <li><a class="dropdown-item text-white py-2" href="{{ route('farmer.dashboard') }}"><i class="bi bi-grid me-2 text-warning"></i>Farmer Portal</a></li>
            @else
              <li><a class="dropdown-item text-white py-2" href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-warning"></i>Customer Portal</a></li>
            @endif
            <li><hr class="dropdown-divider border-white border-opacity-10 my-1"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger py-2">
                  <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                </button>
              </form>
            </li>
          </ul>
        </div>
      @else
        <!-- Login Button -->
        <a href="{{ route('login') }}" class="ref-btn-login text-decoration-none">
          Login
        </a>

        <!-- Sign Up Button -->
        <a href="{{ route('register') }}" class="ref-btn-signup text-decoration-none">
          Sign Up
        </a>
      @endauth
    </div>

  </div>
</nav>

