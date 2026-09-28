<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Join MarketLink — Choose Account Type</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg:       #ffffff;
      --surface:  #f8faf9;
      --border:   #e2e8e5;
      --emerald:  #16845B;
      --green:    #16845B;
      --forest:   #0B2E22;
      --gold:     #D4B477;
      --gold-dark:#9a7020;
      --muted:    #52605b;
      --text:     #111827;
    }

    html, body {
      min-height: 100vh;
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    /* ── Top bar ── */
    .topbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 50;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 2.25rem;
      background: #0B2E22;
      border-bottom: 1px solid rgba(212, 180, 119, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }
    .topbar-logo img { height: 42px; width: auto; object-fit: contain; }
    .topbar-signin {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.84rem;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s;
    }
    .topbar-signin-label {
      color: rgba(255, 255, 255, 0.75);
      font-size: 0.82rem;
    }
    .topbar-signin-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      color: #F5E8C7;
      background: rgba(212, 180, 119, 0.12);
      font-weight: 600;
      font-size: 0.82rem;
      border: 1px solid rgba(212, 180, 119, 0.38);
      padding: 0.4rem 0.95rem;
      border-radius: 8px;
      transition: all 0.2s ease;
    }
    .topbar-signin:hover .topbar-signin-pill {
      border-color: rgba(212, 180, 119, 0.8);
      background: rgba(212, 180, 119, 0.22);
      color: #ffffff;
    }

    /* ── Page wrapper ── */
    .page {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 7.5rem 1.5rem 3.5rem;
    }

    /* ── Header ── */
    .hdr {
      text-align: center;
      max-width: 580px;
      margin-bottom: 2.75rem;
    }
    .hdr-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(22, 132, 91, 0.08);
      border: 1px solid rgba(22, 132, 91, 0.22);
      color: var(--green);
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      padding: 0.32rem 0.9rem;
      border-radius: 6px;
      margin-bottom: 1.25rem;
    }
    .hdr-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.1rem, 4.5vw, 3rem);
      font-weight: 800;
      color: #111827;
      line-height: 1.15;
      letter-spacing: -0.02em;
      margin-bottom: 0.85rem;
    }
    .hdr-title em {
      font-style: italic;
      color: #16845B;
    }
    .hdr-sub {
      font-size: 0.95rem;
      color: var(--muted);
      line-height: 1.65;
    }

    /* â”€â”€ Cards â”€â”€ */
    .cards-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.25rem;
      width: 100%;
      max-width: 820px;
    }
    @media (max-width: 640px) {
      .cards-row { grid-template-columns: 1fr; }
    }

    .card-link {
      display: flex;
      flex-direction: column;
      text-decoration: none;
      border-radius: 16px;
      background: #ffffff;
      border: 1px solid var(--border);
      padding: 2rem 1.75rem;
      transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
    }
    .card-link:hover {
      transform: translateY(-3px);
      text-decoration: none;
    }
    .card-customer:hover {
      border-color: rgba(22,132,91,0.5);
      box-shadow: 0 8px 32px rgba(22,132,91,0.12), 0 0 0 1px rgba(22,132,91,0.1);
    }
    .card-farmer:hover {
      border-color: rgba(212,180,119,0.45);
      box-shadow: 0 8px 32px rgba(212,180,119,0.1), 0 0 0 1px rgba(212,180,119,0.08);
    }

    /* Accent top strip */
    .card-link { position: relative; overflow: hidden; }
    .card-link::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 2px;
      opacity: 0;
      transition: opacity 0.25s ease;
    }
    .card-customer::before {
      background: linear-gradient(90deg, var(--emerald), var(--green));
    }
    .card-farmer::before {
      background: linear-gradient(90deg, #a87d3a, var(--gold), var(--cream));
    }
    .card-link:hover::before { opacity: 1; }

    /* Icon */
    .card-icon {
      width: 56px;
      height: 56px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.55rem;
      margin-bottom: 1.4rem;
      flex-shrink: 0;
      transition: transform 0.25s ease;
    }
    .card-link:hover .card-icon { transform: scale(1.06); }
    .card-customer .card-icon {
      background: rgba(22,132,91,0.14);
      border: 1px solid rgba(22,132,91,0.3);
      color: var(--green);
    }
    .card-farmer .card-icon {
      background: rgba(212,180,119,0.12);
      border: 1px solid rgba(212,180,119,0.28);
      color: #16845B;
    }

    /* Card text */
    .card-type {
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      margin-bottom: 0.3rem;
    }
    .card-customer .card-type { color: var(--green); }
    .card-farmer  .card-type { color: #16845B; }

    .card-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      font-weight: 700;
      color: #111827;
      line-height: 1.3;
      margin-bottom: 0.65rem;
    }
    .card-desc {
      font-size: 0.83rem;
      color: rgba(0,0,0,0.5);
      line-height: 1.7;
      margin-bottom: 1.4rem;
    }

    /* Perks */
    .perks {
      list-style: none;
      padding: 0;
      margin: 0 0 1.75rem;
      flex: 1;
    }
    .perks li {
      display: flex;
      align-items: flex-start;
      gap: 0.55rem;
      font-size: 0.8rem;
      color: rgba(0,0,0,0.68);
      padding: 0.28rem 0;
    }
    .perks li i {
      font-size: 0.72rem;
      margin-top: 2px;
      flex-shrink: 0;
    }
    .card-customer .perks li i { color: var(--green); }
    .card-farmer  .perks li i { color: #16845B; }

    /* CTA row */
    .card-cta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 0.875rem;
      font-weight: 600;
      padding: 0.7rem 1.1rem;
      border-radius: 10px;
      transition: all 0.22s ease;
    }
    .cta-arrow { transition: transform 0.22s ease; }
    .card-link:hover .cta-arrow { transform: translateX(4px); }

    .card-customer .card-cta {
      background: rgba(22,132,91,0.15);
      color: #16845B;
      border: 1px solid rgba(22,132,91,0.28);
    }
    .card-customer:hover .card-cta {
      background: rgba(22,132,91,0.22);
      border-color: rgba(22,132,91,0.4);
    }
    .card-farmer .card-cta {
      background: rgba(212,180,119,0.08);
      color: #9a7020; border: 1px solid rgba(184,146,42,0.25);
    }
    .card-farmer:hover .card-cta {
      background: rgba(212,180,119,0.14);
      border-color: rgba(212,180,119,0.4);
    }

    /* ── Separator ── */
    .sep {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 44px;
      gap: 0;
    }
    .sep-line {
      flex: 1;
      width: 1px;
      background: var(--border);
    }
    .sep-text {
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: #94a3b8;
      background: #ffffff;
      padding: 0.5rem 0;
    }
    @media (max-width: 640px) {
      .sep { flex-direction: row; width: 100%; height: 36px; margin: 0.5rem 0; }
      .sep-line { width: auto; flex: 1; height: 1px; }
      .sep-text { padding: 0 0.75rem; }
    }

    /* Cards + sep wrapper */
    .cards-wrapper {
      display: flex;
      align-items: stretch;
      gap: 0;
      width: 100%;
      max-width: 840px;
    }
    .cards-wrapper .card-link { flex: 1; }
    @media (max-width: 640px) {
      .cards-wrapper { flex-direction: column; }
    }

    /* ── Trust strip ── */
    .trust {
      margin-top: 2.75rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem 1.8rem;
      flex-wrap: wrap;
    }
    .trust-item {
      display: flex;
      align-items: center;
      gap: 0.45rem;
      font-size: 0.8rem;
      font-weight: 500;
      color: #4b5563;
    }
    .trust-item i { color: #16845B; font-size: 0.95rem; }
    .trust-dot {
      width: 4px; height: 4px;
      border-radius: 50%;
      background: #cbd5e1;
    }

    /* ── Footer ── */
    .foot {
      margin-top: 1.75rem;
      text-align: center;
      font-size: 0.85rem;
      color: #64748b;
    }
    .foot a {
      color: #16845B;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }
    .foot a:hover { color: #0B2E22; text-decoration: underline; }
    .foot-sep { margin: 0 0.6rem; color: #cbd5e1; }
  </style>
</head>
<body>

  <!-- Topbar -->
  <nav class="topbar">
    <a href="{{ route('home') }}" class="topbar-logo">
      <img src="{{ asset('images/logo.png') }}" alt="MarketLink">
    </a>
    <a href="{{ route('login') }}" class="topbar-signin">
      <span class="d-none d-sm-inline topbar-signin-label">Already a member?</span>
      <span class="topbar-signin-pill">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </span>
    </a>
  </nav>

  <div class="page">

    <!-- Header -->
    <div class="hdr">
      <div class="hdr-badge">
        <i class="bi bi-circle-fill" style="font-size:0.45rem;"></i>
        Step 1 of 2 — Getting Started
      </div>
      <h1 class="hdr-title">
        Choose Your<br><em>Account Type</em>
      </h1>
      <p class="hdr-sub">
        Tell us how you'll use MarketLink. Your dashboard and features will be set up accordingly.
      </p>
    </div>

    <!-- Cards -->
    <div class="cards-wrapper">

      <!-- Customer -->
      <a href="{{ route('register.customer') }}" class="card-link card-customer" id="choose-customer-btn">
        <div class="card-icon"><i class="bi bi-bag-heart-fill"></i></div>
        <div class="card-type">Customer</div>
        <div class="card-title">Shop &amp; Reserve Fresh Produce</div>
        <p class="card-desc">Browse local farmers, discover seasonal produce, and pre-reserve items before market day. Pay cash at pickup — no card ever needed.</p>
        <ul class="perks">
          <li><i class="bi bi-check-lg"></i> Browse verified local farmers &amp; stalls</li>
          <li><i class="bi bi-check-lg"></i> Reserve produce before market day</li>
          <li><i class="bi bi-check-lg"></i> Track orders &amp; leave reviews</li>
          <li><i class="bi bi-check-lg"></i> Favourites list &amp; family sharing</li>
          <li><i class="bi bi-check-lg"></i> Free to join — always</li>
        </ul>
        <div class="card-cta">
          <span>Continue as Customer</span>
          <i class="bi bi-arrow-right cta-arrow"></i>
        </div>
      </a>

      <!-- Separator -->
      <div class="sep">
        <div class="sep-line"></div>
        <div class="sep-text">or</div>
        <div class="sep-line"></div>
      </div>

      <!-- Farmer -->
      <a href="{{ route('register.farmer') }}" class="card-link card-farmer" id="choose-farmer-btn">
        <div class="card-icon"><i class="bi bi-tree-fill"></i></div>
        <div class="card-type">Farmer / Vendor</div>
        <div class="card-title">List Your Stall &amp; Grow Your Sales</div>
        <p class="card-desc">Create your farmer profile, publish your produce listings, manage pickup slots and connect with a loyal community of local buyers.</p>
        <ul class="perks">
          <li><i class="bi bi-check-lg"></i> Dedicated public farmer storefront</li>
          <li><i class="bi bi-check-lg"></i> Manage weekly stock &amp; pickup slots</li>
          <li><i class="bi bi-check-lg"></i> Receive &amp; track customer reservations</li>
          <li><i class="bi bi-check-lg"></i> Sales analytics &amp; insights dashboard</li>
          <li><i class="bi bi-check-lg"></i> Reviewed &amp; approved by our team</li>
        </ul>
        <div class="card-cta">
          <span>Continue as Farmer</span>
          <i class="bi bi-arrow-right cta-arrow"></i>
        </div>
      </a>

    </div>
    <!-- /cards -->

    <!-- Trust strip -->
    <div class="trust">
      <div class="trust-item"><i class="bi bi-shield-check"></i> Secure &amp; Private</div>
      <div class="trust-dot"></div>
      <div class="trust-item"><i class="bi bi-cash-coin"></i> No Card Required</div>
      <div class="trust-dot"></div>
      <div class="trust-item"><i class="bi bi-patch-check"></i> Verified Farmers Only</div>
      <div class="trust-dot"></div>
      <div class="trust-item"><i class="bi bi-people"></i> Community-Driven</div>
    </div>

    <!-- Footer -->
    <p class="foot">
      Already have an account? <a href="{{ route('login') }}">Sign in here</a>
      <span class="foot-sep">&bull;</span>
      <a href="{{ route('home') }}">Back to MarketLink</a>
    </p>

  </div>

</body>
</html>

