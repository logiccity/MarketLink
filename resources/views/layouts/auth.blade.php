<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Authentication') — MarketLink eGreen Basket</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/marketlink.css') }}">

  <style>
    .auth-page {
      min-height: 100vh;
      background-color: var(--color-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem 1rem;
    }
    .auth-container-lux {
      width: 100%;
      max-width: 1040px;
      background: var(--color-surface);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow-lux-lg);
      overflow: hidden;
    }
    .auth-sidebar-lux {
      background: linear-gradient(155deg, rgba(10, 32, 24, 0.86) 0%, rgba(14, 46, 35, 0.80) 50%, rgba(8, 26, 20, 0.92) 100%),
                  url('{{ asset('images/auth-sidebar-bg.jpg') }}') center center / cover no-repeat;
      color: #FFFFFF;
      padding: 3.5rem 2.8rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      position: relative;
      border-right: 1px solid rgba(201, 168, 106, 0.25);
    }
    .auth-sidebar-lux::before {
      content: '';
      position: absolute;
      top: -30px;
      right: -30px;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(201, 168, 106, 0.2) 0%, transparent 70%);
      pointer-events: none;
    }
  </style>
</head>
<body class="auth-page">

  <div class="auth-container-lux">
    <div class="row g-0">
      
      <!-- Editorial Left Panel (Desktop) -->
      <div class="col-lg-5 d-none d-lg-block">
        <div class="auth-sidebar-lux">
          <div>
            <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none mb-5">
              <img src="{{ asset('images/logo.png') }}" alt="MarketLink Logo" style="height: 54px; width: auto; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));">
            </a>

            <div class="hero-editorial-badge mb-3" style="background: rgba(14, 41, 32, 0.65); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(201, 168, 106, 0.4); box-shadow: 0 4px 16px rgba(0,0,0,0.3);">
              Exclusive Community Access
            </div>

            <h2 class="text-white mb-3" style="font-family: var(--font-serif); font-size: 2rem; line-height: 1.25; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
              Pure Local Harvest,<br>
              <em style="color: #F3DEB3;">Reserved for You</em>
            </h2>

            <p class="text-white text-opacity-90 small mb-4" style="line-height: 1.7; text-shadow: 0 1px 6px rgba(0,0,0,0.6);">
              Connect directly with verified independent regional farmers. Lock in fresh produce before weekend market cutoffs.
            </p>
          </div>

          <div class="p-3 rounded-4" style="background: rgba(10, 32, 24, 0.72); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1px solid rgba(201, 168, 106, 0.35); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);">
            <div class="d-flex align-items-center gap-2 text-gold small fw-bold mb-1" style="color: #E6C887;">
              <i class="bi bi-shield-check fs-5" style="color: #22c55e;"></i>
              <span>The eGreen Standard</span>
            </div>
            <div class="small text-white text-opacity-85" style="font-size: 0.75rem; line-height: 1.5;">
              No online card transactions. Zero delivery fees. Cash upon personal stall pickup.
            </div>
          </div>
        </div>
      </div>

      <!-- Right Form Column -->
      <div class="col-lg-7">
        <div class="p-4 p-md-5">

          <div class="d-lg-none text-center mb-4">
            <a href="{{ route('home') }}" class="d-inline-flex align-items-center text-decoration-none">
              <img src="{{ asset('images/logo.png') }}" alt="MarketLink Logo" style="height: 46px; width: auto; object-fit: contain;">
            </a>
          </div>

          @if(session('success'))
            <div class="alert alert-success border-0 rounded-3 mb-3 small" style="background: #EAF5EE; color: #1B4D36;">
              <i class="bi bi-check-circle-fill me-2 text-success"></i>{{ session('success') }}
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-3 small" style="background: #FDF2F2; color: #6E2222;">
              <i class="bi bi-exclamation-octagon-fill me-2 text-danger"></i>{{ session('error') }}
            </div>
          @endif

          @if(session('warning'))
            <div class="alert alert-warning border-0 rounded-3 mb-3 small" style="background: #FEF7EC; color: #7B4F0B;">
              <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>{{ session('warning') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-3 small" style="background: #FDF2F2; color: #6E2222;">
              <strong>Please check the following:</strong>
              <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          @yield('content')

        </div>
      </div>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
