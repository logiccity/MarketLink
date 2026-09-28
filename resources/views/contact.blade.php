@extends('layouts.app')

@section('title', 'Concierge & Community Support — MarketLink')
@section('meta_description', 'Connect with the MarketLink eGreen Basket community support desk for questions regarding markets, pre-orders, and grower onboarding.')

@section('content')
{{-- Hero Banner --}}
<section class="page-hero-banner py-5 py-lg-6" style="background-image: url('{{ asset('images/contact-hero-market.jpg') }}');">
  <div class="container hero-content-rel text-center" style="max-width: 820px;">
    
    <div class="d-inline-flex mb-3">
      <span class="hero-tag-badge">
        <i class="bi bi-chat-heart text-warning"></i>
        <span>Community Concierge &bull; Direct Assistance</span>
      </span>
    </div>

    <h1 class="display-5 fw-bold text-white mb-3" style="font-family: var(--font-serif); letter-spacing: -0.02em;">
      We Are Here <span style="color: #D4B477;">For You</span>
    </h1>

    <p class="lead text-white-50 mx-auto mb-4" style="font-size: 1.1rem; line-height: 1.7; max-width: 660px;">
      Whether inquiring about market stall placements, community partnerships, or harvest scheduling assistance, our community desk responds promptly.
    </p>

    {{-- Quick Value Chips --}}
    <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 gap-md-3">
      <span class="badge rounded-pill px-3 py-2 text-white" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.8rem; font-weight: 500;">
        <i class="bi bi-geo-alt-fill text-success me-1"></i> Neighborhood Market Hubs
      </span>
      <span class="badge rounded-pill px-3 py-2 text-white" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.8rem; font-weight: 500;">
        <i class="bi bi-clock-history text-warning me-1"></i> Prompt Response Dispatch
      </span>
      <span class="badge rounded-pill px-3 py-2 text-white" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); font-size: 0.8rem; font-weight: 500;">
        <i class="bi bi-cash-stack text-success me-1"></i> Zero Digital Surcharges
      </span>
    </div>

  </div>
</section>

{{-- Main Content Grid --}}
<section class="py-5 py-lg-6" style="background: var(--color-bg);">
  <div class="container" style="max-width: 1180px;">
    <div class="row g-4 g-lg-5 align-items-stretch">
      
      {{-- Left: Contact Info Card --}}
      <div class="col-lg-5">
        <div class="contact-info-card-lux">
          
          <div class="mb-4">
            <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase d-inline-block" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
              Community Desk
            </span>
            <h3 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 1.85rem;">
              eGreen Basket Network
            </h3>
            <p class="text-muted small mb-0" style="line-height: 1.6; font-size: 0.88rem;">
              Dedicated to fostering authentic regenerative agrarian networks across neighborhood community markets.
            </p>
          </div>

          {{-- Contact Info Rows --}}
          <div class="d-flex flex-column gap-4 my-auto py-3">
            
            {{-- Address --}}
            <div class="contact-item-row">
              <div class="contact-item-icon">
                <i class="bi bi-geo-alt-fill"></i>
              </div>
              <div>
                <div class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.06em; font-size: 0.72rem;">Community Operations</div>
                <div class="text-muted small mt-1" style="line-height: 1.5;">
                  MarketLink Central Hub, Green Agriculture Complex
                </div>
              </div>
            </div>

            {{-- Email --}}
            <div class="contact-item-row">
              <div class="contact-item-icon">
                <i class="bi bi-envelope-fill"></i>
              </div>
              <div>
                <div class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.06em; font-size: 0.72rem;">Email Dispatch</div>
                <div class="text-muted small mt-1">
                  <a href="mailto:support@egreenbasket.org" class="text-decoration-none text-dark fw-medium">support@egreenbasket.org</a>
                </div>
              </div>
            </div>

            {{-- Phone & Support Hours --}}
            <div class="contact-item-row">
              <div class="contact-item-icon">
                <i class="bi bi-telephone-fill"></i>
              </div>
              <div>
                <div class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.06em; font-size: 0.72rem;">Grower &amp; Patron Hotline</div>
                <div class="text-muted small mt-1">
                  +92 300 1234567 <span class="text-muted">(Mon – Sat, 8:30 AM – 6:00 PM)</span>
                </div>
              </div>
            </div>

            {{-- Collection Days --}}
            <div class="contact-item-row">
              <div class="contact-item-icon">
                <i class="bi bi-calendar2-check-fill"></i>
              </div>
              <div>
                <div class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.06em; font-size: 0.72rem;">Market Pickup Windows</div>
                <div class="text-muted small mt-1">
                  Weekend Dawn Collection (Saturday &amp; Sunday, 7:00 AM – 1:00 PM)
                </div>
              </div>
            </div>

          </div>

          {{-- Direct Stall Pickup Notice --}}
          <div class="mt-4 p-3 rounded-4" style="background: var(--color-sage-soft); border: 1px solid rgba(168, 201, 160, 0.4);">
            <div class="d-flex align-items-start gap-2">
              <i class="bi bi-info-circle-fill text-success mt-1 flex-shrink-0"></i>
              <div class="text-muted small" style="font-size: 0.82rem; line-height: 1.55;">
                <strong class="text-dark">Stall Pickup Coordination:</strong> For morning-of harvest adjustments or arrival notes, communicate directly with your producer via the stall contact listed on your pre-order voucher.
              </div>
            </div>
          </div>

        </div>
      </div>

      {{-- Right: Inquiry Message Form --}}
      <div class="col-lg-7">
        <div class="contact-form-card-lux h-100 d-flex flex-column">
          
          <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1" style="font-family: var(--font-serif); letter-spacing: -0.01em; font-size: 1.85rem;">
              Send an Inquiry
            </h3>
            <p class="text-muted small mb-0">Fill out your details below and our concierge desk will follow up promptly.</p>
          </div>

          @if(session('success'))
            <div class="alert border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3 p-3" style="background: rgba(20, 83, 45, 0.08); border-left: 4px solid var(--color-success) !important; color: var(--color-primary);">
              <i class="bi bi-check-circle-fill fs-5 text-success"></i>
              <div class="fw-medium small">{{ session('success') }}</div>
            </div>
          @endif

          <form action="{{ route('contact.submit') }}" method="POST" class="my-auto">
            @csrf
            
            <div class="row g-3 g-md-4">
              
              {{-- Full Name --}}
              <div class="col-md-6">
                <label for="name" class="form-label small fw-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.05em; font-size: 0.74rem;">
                  Full Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()?->name) }}" placeholder="e.g. Ayesha Khan" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Email Address --}}
              <div class="col-md-6">
                <label for="email" class="form-label small fw-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.05em; font-size: 0.74rem;">
                  Email Address <span class="text-danger">*</span>
                </label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()?->email) }}" placeholder="name@example.com" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Subject --}}
              <div class="col-12">
                <label for="subject" class="form-label small fw-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.05em; font-size: 0.74rem;">
                  Subject of Inquiry <span class="text-danger">*</span>
                </label>
                <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="e.g. Grower registration question or pre-order assistance" required>
                @error('subject')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Message Details --}}
              <div class="col-12">
                <label for="message" class="form-label small fw-bold text-dark text-uppercase mb-1" style="letter-spacing: 0.05em; font-size: 0.74rem;">
                  Message Details <span class="text-danger">*</span>
                </label>
                <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Describe your question, partnership idea, or feedback..." required>{{ old('message') }}</textarea>
                @error('message')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Submit Button --}}
              <div class="col-12 pt-2">
                <button type="submit" class="btn btn-lux-primary px-5 py-3 d-inline-flex align-items-center gap-2" style="font-size: 0.95rem; border-radius: var(--radius-pill); font-weight: 600;">
                  <i class="bi bi-send-fill"></i>
                  <span>Transmit Message</span>
                </button>
              </div>

            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</section>

{{-- Support Links Grid --}}
<section class="py-5" style="background: #ffffff; border-top: 1px solid var(--color-border-subtle);">
  <div class="container" style="max-width: 1180px;">
    
    <div class="text-center mb-4">
      <h4 class="fw-bold text-dark mb-1" style="font-family: var(--font-serif); font-size: 1.6rem;">
        Looking for Quick Answers?
      </h4>
      <p class="text-muted small mb-0">Browse common inquiries or explore our active market venues.</p>
    </div>

    <div class="row g-3 g-md-4">
      
      {{-- Card 1: Pre-Orders FAQ --}}
      <div class="col-md-4">
        <a href="{{ route('faq') }}" class="help-quick-card">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-question-circle-fill text-success fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">Pre-Order FAQs</h6>
          </div>
          <p class="text-muted small mb-0" style="line-height: 1.5;">
            Learn how dawn harvest schedules, pickup windows, and stall cash settlements operate.
          </p>
          <div class="mt-3 text-success fw-semibold small d-inline-flex align-items-center gap-1">
            <span>Read FAQs</span> <i class="bi bi-arrow-right"></i>
          </div>
        </a>
      </div>

      {{-- Card 2: Grower Registration --}}
      <div class="col-md-4">
        <a href="{{ route('register.farmer') }}" class="help-quick-card">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-shop text-warning fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">Become a Grower</h6>
          </div>
          <p class="text-muted small mb-0" style="line-height: 1.5;">
            Are you a local producer? Register your farm stall and start receiving verified reservations.
          </p>
          <div class="mt-3 text-warning fw-semibold small d-inline-flex align-items-center gap-1">
            <span>Join Community</span> <i class="bi bi-arrow-right"></i>
          </div>
        </a>
      </div>

      {{-- Card 3: Find Markets --}}
      <div class="col-md-4">
        <a href="{{ route('markets.index') }}" class="help-quick-card">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
            <h6 class="fw-bold text-dark mb-0">Market Locations</h6>
          </div>
          <p class="text-muted small mb-0" style="line-height: 1.5;">
            Discover schedules, directions, and participating farmers for nearby neighborhood hubs.
          </p>
          <div class="mt-3 text-primary fw-semibold small d-inline-flex align-items-center gap-1">
            <span>Explore Hubs</span> <i class="bi bi-arrow-right"></i>
          </div>
        </a>
      </div>

    </div>
  </div>
</section>

{{-- Interactive Operations Hub Map --}}
<section class="py-5" style="background: #f8fafc; border-top: 1px solid var(--color-border-subtle);">
  <div class="container" style="max-width: 1180px;">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
      <div>
        <span class="badge rounded-pill px-3 py-1 mb-2 text-uppercase d-inline-block" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 700;">
          HQ &amp; Help Desk Coordinates
        </span>
        <h4 class="fw-bold text-dark mb-0" style="font-family: var(--font-serif); font-size: 1.65rem;">
          Find Our Central Operations Hub
        </h4>
        <p class="text-muted small mb-0">Visit our grower onboarding office or community outreach desk.</p>
      </div>
      <div>
        <a href="https://www.google.com/maps/dir/?api=1&destination=31.5204,74.3587" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="font-size: 0.88rem;">
          <i class="bi bi-compass-fill"></i>
          <span>Open in Google Maps</span>
          <i class="bi bi-box-arrow-up-right small"></i>
        </a>
      </div>
    </div>

    <div class="card border-0 rounded-4 overflow-hidden shadow-sm" style="border: 1px solid rgba(0,0,0,0.06); height: 380px;">
      <div id="contact-map" style="width: 100%; height: 100%;"></div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapEl = document.getElementById('contact-map');
  if (!mapEl) return;

  const lat = 31.5204;
  const lng = 74.3587;

  const map = L.map('contact-map', { scrollWheelZoom: false }).setView([lat, lng], 14);
  L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: '&copy; Google Maps'
  }).addTo(map);

  const marker = L.marker([lat, lng])
    .addTo(map)
    .bindPopup('<strong>eGreen Basket Central Hub</strong><br><small>Green Agriculture Complex, Gulberg III, Lahore</small><br><a href="https://www.google.com/maps/dir/?api=1&destination=' + lat + ',' + lng + '" target="_blank" class="text-success fw-bold text-decoration-none mt-1 d-inline-block">Get Directions &rarr;</a>')
    .openPopup();
});
</script>
@endpush
