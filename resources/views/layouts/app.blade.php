<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta_description', 'MarketLink by eGreen Basket — Reserve fresh produce from local farmers at your community market.')">
  <title>@yield('title', 'MarketLink') — eGreen Basket</title>

  <!-- Google Fonts: Playfair Display + Plus Jakarta Sans + Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <!-- MarketLink Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/marketlink.css') }}">

  @stack('styles')
</head>
<body class="storefront-body {{ request()->routeIs('home') ? 'hero-page' : '' }}">

  <x-navbar />

  {{-- ╔══════════════════════════════════════════════════════╗
       ║   PREMIUM FLOATING TOAST NOTIFICATION SYSTEM        ║
       ╚══════════════════════════════════════════════════════╝ --}}

  @if(session('success') || session('error') || session('warning') || session('info') || $errors->any())
  <style>
    /* Toast Container */
    #ml-toast-container {
      position: fixed;
      bottom: 28px;
      right: 28px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 12px;
      pointer-events: none;
      max-width: 390px;
      width: calc(100vw - 40px);
    }

    /* Toast Base */
    .ml-toast {
      pointer-events: all;
      background: rgba(10, 22, 18, 0.96);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 16px;
      border: 1px solid rgba(255,255,255,0.08);
      padding: 16px 18px 14px;
      box-shadow:
        0 20px 60px rgba(0,0,0,0.45),
        0 4px 16px rgba(0,0,0,0.25),
        inset 0 1px 0 rgba(255,255,255,0.06);
      display: flex;
      align-items: flex-start;
      gap: 14px;
      position: relative;
      overflow: hidden;
      transform: translateX(120%);
      opacity: 0;
      animation: ml-toast-in 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .ml-toast.ml-toast-out {
      animation: ml-toast-out 0.35s cubic-bezier(0.4, 0, 1, 1) forwards;
    }

    @keyframes ml-toast-in {
      from { transform: translateX(120%); opacity: 0; }
      to   { transform: translateX(0);    opacity: 1; }
    }
    @keyframes ml-toast-out {
      from { transform: translateX(0);    opacity: 1; max-height: 200px; margin-bottom: 0; }
      to   { transform: translateX(120%); opacity: 0; max-height: 0;   margin-bottom: -12px; }
    }

    /* Accent strip (left color bar) */
    .ml-toast::before {
      content: '';
      position: absolute;
      left: 0; top: 0; bottom: 0;
      width: 4px;
      border-radius: 16px 0 0 16px;
    }
    .ml-toast-success::before { background: linear-gradient(180deg, #22c55e, #16a34a); }
    .ml-toast-error::before   { background: linear-gradient(180deg, #ef4444, #dc2626); }
    .ml-toast-warning::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
    .ml-toast-info::before    { background: linear-gradient(180deg, #3b82f6, #2563eb); }

    /* Icon bubble */
    .ml-toast-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.15rem;
      flex-shrink: 0;
      margin-left: 6px;
    }
    .ml-toast-success .ml-toast-icon { background: rgba(34,197,94,0.15);  color: #86efac; }
    .ml-toast-error   .ml-toast-icon { background: rgba(239,68,68,0.15);  color: #fca5a5; }
    .ml-toast-warning .ml-toast-icon { background: rgba(245,158,11,0.15); color: #fde68a; }
    .ml-toast-info    .ml-toast-icon { background: rgba(59,130,246,0.15); color: #93c5fd; }

    /* Content */
    .ml-toast-body { flex: 1; min-width: 0; }
    .ml-toast-title {
      font-size: 0.82rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.07em;
      margin-bottom: 3px;
    }
    .ml-toast-success .ml-toast-title { color: #86efac; }
    .ml-toast-error   .ml-toast-title { color: #fca5a5; }
    .ml-toast-warning .ml-toast-title { color: #fde68a; }
    .ml-toast-info    .ml-toast-title { color: #93c5fd; }

    .ml-toast-msg {
      font-size: 0.89rem;
      color: rgba(255,255,255,0.82);
      line-height: 1.45;
      margin: 0;
    }

    /* Close button */
    .ml-toast-close {
      background: none;
      border: none;
      color: rgba(255,255,255,0.35);
      font-size: 1rem;
      cursor: pointer;
      padding: 0;
      line-height: 1;
      flex-shrink: 0;
      transition: color 0.2s;
      margin-top: 1px;
    }
    .ml-toast-close:hover { color: rgba(255,255,255,0.8); }

    /* Progress bar */
    .ml-toast-progress {
      position: absolute;
      bottom: 0; left: 0;
      height: 3px;
      border-radius: 0 0 16px 16px;
      width: 100%;
      transform-origin: left;
      animation: ml-progress 5s linear forwards;
    }
    .ml-toast-success .ml-toast-progress { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .ml-toast-error   .ml-toast-progress { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .ml-toast-warning .ml-toast-progress { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .ml-toast-info    .ml-toast-progress { background: linear-gradient(90deg, #3b82f6, #2563eb); }

    @keyframes ml-progress {
      from { transform: scaleX(1); }
      to   { transform: scaleX(0); }
    }

    /* Error list */
    .ml-toast-errors {
      list-style: none;
      padding: 0;
      margin: 4px 0 0;
    }
    .ml-toast-errors li {
      font-size: 0.83rem;
      color: rgba(255,255,255,0.7);
      padding: 2px 0;
      display: flex;
      align-items: flex-start;
      gap: 6px;
    }
    .ml-toast-errors li::before {
      content: '•';
      color: #fca5a5;
      flex-shrink: 0;
      margin-top: 1px;
    }

    @media (max-width: 575px) {
      #ml-toast-container {
        bottom: 16px;
        right: 16px;
        left: 16px;
        width: auto;
      }
    }
  </style>

  <div id="ml-toast-container">

    @if(session('success'))
    <div class="ml-toast ml-toast-success" id="ml-toast-success">
      <div class="ml-toast-icon"><i class="bi bi-check-circle-fill"></i></div>
      <div class="ml-toast-body">
        <div class="ml-toast-title">Success</div>
        <p class="ml-toast-msg">{{ session('success') }}</p>
      </div>
      <button class="ml-toast-close" onclick="mlDismissToast('ml-toast-success')" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="ml-toast-progress"></div>
    </div>
    @endif

    @if(session('error'))
    <div class="ml-toast ml-toast-error" id="ml-toast-error">
      <div class="ml-toast-icon"><i class="bi bi-x-circle-fill"></i></div>
      <div class="ml-toast-body">
        <div class="ml-toast-title">Error</div>
        <p class="ml-toast-msg">{{ session('error') }}</p>
      </div>
      <button class="ml-toast-close" onclick="mlDismissToast('ml-toast-error')" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="ml-toast-progress"></div>
    </div>
    @endif

    @if(session('warning'))
    <div class="ml-toast ml-toast-warning" id="ml-toast-warning">
      <div class="ml-toast-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <div class="ml-toast-body">
        <div class="ml-toast-title">Warning</div>
        <p class="ml-toast-msg">{{ session('warning') }}</p>
      </div>
      <button class="ml-toast-close" onclick="mlDismissToast('ml-toast-warning')" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="ml-toast-progress"></div>
    </div>
    @endif

    @if(session('info'))
    <div class="ml-toast ml-toast-info" id="ml-toast-info">
      <div class="ml-toast-icon"><i class="bi bi-info-circle-fill"></i></div>
      <div class="ml-toast-body">
        <div class="ml-toast-title">Notice</div>
        <p class="ml-toast-msg">{{ session('info') }}</p>
      </div>
      <button class="ml-toast-close" onclick="mlDismissToast('ml-toast-info')" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="ml-toast-progress"></div>
    </div>
    @endif

    @if($errors->any())
    <div class="ml-toast ml-toast-error" id="ml-toast-errors">
      <div class="ml-toast-icon"><i class="bi bi-exclamation-octagon-fill"></i></div>
      <div class="ml-toast-body">
        <div class="ml-toast-title">Please fix the following</div>
        <ul class="ml-toast-errors">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <button class="ml-toast-close" onclick="mlDismissToast('ml-toast-errors')" aria-label="Close">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    @endif

  </div>

  <script>
    function mlDismissToast(id) {
      const el = document.getElementById(id);
      if (!el) return;
      el.classList.add('ml-toast-out');
      el.addEventListener('animationend', () => el.remove(), { once: true });
    }
    // Auto-dismiss after 5.5s
    document.addEventListener('DOMContentLoaded', function () {
      ['ml-toast-success','ml-toast-error','ml-toast-warning','ml-toast-info'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) setTimeout(() => mlDismissToast(id), 5500);
      });
    });
  </script>
  @endif


  <main>
    @yield('content')
  </main>

  <!-- AI Assistant Floating Widget -->
  <div class="ai-assistant-bubble">
    <button type="button" class="ai-bubble-btn" data-bs-toggle="modal" data-bs-target="#aiAssistantModal" title="eGreen Basket AI Market Assistant">
      <span class="robot-icon">🤖</span>
      <span class="ai-live-pulse"></span>
    </button>
  </div>

  <!-- AI Assistant Modal -->
  <div class="modal fade" id="aiAssistantModal" tabindex="-1" aria-labelledby="aiAssistantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-bottom" style="position: fixed; bottom: 95px; right: 25px; margin: 0; max-width: 400px;">
      <div class="modal-content ai-modal-box">
        <div class="ai-modal-header d-flex align-items-center justify-content-between">
          <div>
            <div class="fw-bold d-flex align-items-center gap-2">
              <span class="fs-5">🤖</span> MarketLink AI Assistant
            </div>
            <div class="small text-white text-opacity-75">Instant answers for markets, produce & pre-orders</div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="ai-chat-messages" id="ai-chat-messages">
          <div class="chat-bubble bot">
            <strong>Hello! I'm your eGreen Basket Assistant 🌿</strong><br>
            I can help you find fresh produce, locate farmers markets, and understand how pickup pre-orders work.<br><br>
            <div class="d-flex flex-wrap gap-1 mt-2">
              <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('Where can I find fresh vegetables on Saturday?')">Vegetables on Saturday?</button>
              <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('How does market pickup work?')">How does pickup work?</button>
              <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill" onclick="sendAiPrompt('Show me local farmers')">Show local farmers</button>
            </div>
          </div>
        </div>
        <div class="p-3 border-top bg-white">
          <form id="ai-assistant-form" class="d-flex gap-2">
            <input type="text" id="ai-user-prompt" class="form-control form-control-sm rounded-pill" placeholder="Ask about markets, produce, farmers..." required maxlength="400">
            <button type="submit" class="btn btn-egreen btn-sm rounded-pill px-3">
              <i class="bi bi-send-fill"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <x-footer />

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <!-- MarketLink JS -->
  <script src="{{ asset('js/marketlink.js') }}"></script>

  @stack('scripts')
</body>
</html>
