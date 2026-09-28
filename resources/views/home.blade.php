@extends('layouts.app')

@section('title', 'MarketLink — Fresh From Local Farmers')
@section('meta_description', 'Discover fresh, seasonal produce, explore nearby markets and farmers, and reserve your favorites for convenient pickup at community stalls.')

@section('content')

@php
  $heroVideoUrl = asset('hero-video.mp4');
@endphp

<section class="ref-coded-hero" id="hero">

  @if(!empty($heroVideoUrl))
    <video
      class="ref-hero-video-bg"
      id="heroVideoBg"
      src="{{ $heroVideoUrl }}"
      autoplay muted loop playsinline preload="auto" aria-hidden="true"
    ></video>
  @endif

  <div class="ref-hero-bg-overlay"></div>

  <div class="container-xl position-relative d-flex align-items-center" style="z-index:5; min-height:100vh;">

    <!-- Hero Content: Left-aligned, cinematic, minimal -->
    <div class="hero-content-col">

      <div class="hero-eyebrow" data-aos="fade-up">
        <span class="hero-eyebrow-dot"></span>
        FRESH FROM LOCAL FARMERS
      </div>

      <h1 class="hero-main-heading" data-aos="fade-up" data-aos-delay="80">
        Fresh Food.<br>
        <span class="hero-heading-accent">Local Farmers.</span><br>
        Better Living.
      </h1>

      <p class="hero-main-sub" data-aos="fade-up" data-aos-delay="160">
        Discover fresh seasonal produce from local farmers and reserve your
        favorites for convenient pickup.
      </p>

      
      <form action="{{ route('products.index') }}" method="GET" class="hero-search-wrap" data-aos="fade-up" data-aos-delay="240" id="hero-search-form">
        <i class="bi bi-search hero-search-icon-new"></i>
        <input
          type="text"
          name="search"
          class="hero-search-field"
          placeholder="Search products, farmers or markets..."
          autocomplete="off"
          id="hero-search-input"
        >
        <button type="submit" class="hero-search-submit" aria-label="Search">
          <i class="bi bi-arrow-right"></i>
        </button>
      </form>

      
      <div data-aos="fade-up" data-aos-delay="320">
        <a href="{{ route('markets.index') }}" class="hero-cta-btn" id="hero-explore-markets-btn">
          Explore Markets
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>

    </div>

  </div>

  @if(!empty($heroVideoUrl))
    <button type="button" class="hero-sound-btn" id="heroSoundToggle" aria-label="Toggle Video Sound">
      <i class="bi bi-volume-mute-fill" id="heroSoundIcon"></i>
      <span id="heroSoundLabel">Sound</span>
    </button>
  @endif

  
  <a href="#categories" class="hero-scroll-indicator" aria-label="Scroll to content">
    <div class="hero-mouse-pill"><div class="hero-mouse-dot"></div></div>
  </a>

</section>

@if(isset($announcements) && $announcements->isNotEmpty())
  <section class="py-3" style="background: linear-gradient(90deg, #123C2F, #1c5240); border-bottom: 2px solid #C9A86A;">
    <div class="container-xl">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 text-white">
        <div class="d-flex align-items-center gap-3 overflow-hidden">
          <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold flex-shrink-0" style="background: #C9A86A; color: #123C2F; font-size: 0.72rem; letter-spacing: 0.5px;">
            Announcement
          </span>
          <div class="text-truncate">
            <strong class="me-2">{{ $announcements->first()->title }}:</strong>
            <span class="opacity-90 small">{{ Str::limit($announcements->first()->content, 110) }}</span>
          </div>
        </div>
        <a href="{{ route('about') }}" class="btn btn-sm text-nowrap rounded-pill px-3 fw-semibold flex-shrink-0 text-decoration-none" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); font-size: 0.78rem;">
          Learn More &rarr;
        </a>
      </div>
    </div>
  </section>
@endif


<section class="ref-section-categories" id="categories">
  <div class="container-xl">
    <div class="text-center mb-4 pb-2">
      <div class="ref-section-tag mb-2">Shop by Category</div>
      <h2 class="ref-section-title mb-2">Fresh Picks for a Healthier You</h2>
      <p class="ref-section-sub">Explore a wide range of seasonal and local products from trusted farmers.</p>
    </div>
    <div class="row g-3 g-md-4">
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index', ['category' => 'vegetables']) }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-vegetables.jpg') }}" alt="Fresh Vegetables" loading="lazy"></div>
          <div class="ref-cat-name">Vegetables</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index', ['category' => 'fruits']) }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-fruits.jpg') }}" alt="Seasonal Fruits" loading="lazy"></div>
          <div class="ref-cat-name">Fruits</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index', ['category' => 'dairy']) }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-dairy.jpg') }}" alt="Farm Dairy and Eggs" loading="lazy"></div>
          <div class="ref-cat-name">Dairy &amp; Eggs</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index', ['category' => 'bakery']) }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-bakery.jpg') }}" alt="Artisan Bakery" loading="lazy"></div>
          <div class="ref-cat-name">Bakery</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index', ['category' => 'organic']) }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-organic.jpg') }}" alt="Organic Products" loading="lazy"></div>
          <div class="ref-cat-name">Organic &amp; Natural</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('products.index') }}" class="ref-cat-card">
          <div class="ref-cat-img-box"><img src="{{ asset('images/cat-other.jpg') }}" alt="Other Products" loading="lazy"></div>
          <div class="ref-cat-name">Other Products</div><div class="ref-cat-link">View All &rarr;</div>
        </a>
      </div>
    </div>
  </div>
</section>


@if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
<section class="py-5" style="background: #F4F6F0;">
  <div class="container-xl">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-4 pb-2 gap-3">
      <div>
        <div class="ref-section-tag justify-content-start mb-2" style="margin-left:-12px;">Weekly Harvest</div>
        <h2 class="ref-section-title mb-1">Featured Fresh Produce</h2>
        <p class="ref-section-sub mx-0 text-start">Hand-picked seasonal goods freshly harvested by verified local growers.</p>
      </div>
      <div>
        <a href="{{ route('products.index') }}" class="ref-btn-view-all-farmers">
          Browse All Produce &rarr;
        </a>
      </div>
    </div>
    <div class="row g-3 g-md-4">
      @foreach($featuredProducts as $prod)
        <div class="col-6 col-md-4 col-lg-3">
          <x-product-card :product="$prod" />
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif


<section class="ref-section-map" id="find-farmers">
  <div class="container-xl">
    <div class="row g-4 align-items-stretch">

      <div class="col-lg-4">
        <div class="ref-map-card position-relative overflow-hidden">
          <div id="ref-leaflet-map"></div>
          @php $floatingMarket = $featuredMarkets->first(); @endphp
          @if($floatingMarket)
            <div class="ref-map-floating-card d-flex align-items-center gap-2">
              <div class="ref-map-floating-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <div>
                <div class="fw-bold text-dark" style="font-size:0.85rem;">{{ $floatingMarket->name }}</div>
                <div class="text-muted" style="font-size:0.72rem;">{{ $floatingMarket->products_count ?? 0 }} products &bull; {{ $floatingMarket->farmers_count ?? 0 }} farmers</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                  <span class="text-muted fw-semibold" style="font-size:0.7rem;">📍 {{ $floatingMarket->city ?? 'Local Area' }}</span>
                  <a href="{{ route('markets.show', $floatingMarket) }}" class="text-success fw-bold text-decoration-none" style="font-size:0.72rem;">View Market &rarr;</a>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>

      <div class="col-lg-5">
        <div class="ref-search-card d-flex flex-column justify-content-between">
          <div>
            <h3 class="ref-section-title mb-1" style="font-size:1.42rem;">Find Farmers Near You</h3>
            <p class="text-muted small mb-3">Discover local markets and farmers in your area.</p>
            <form action="{{ route('products.index') }}" method="GET" class="mb-4">
              <div class="ref-search-input-group">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="ref-search-input" placeholder="Search markets, farmers or products..." autocomplete="off">
              </div>
              <div class="row g-2 mb-3">
                <div class="col-4">
                  <select name="market" class="ref-select w-100">
                    <option value="">All Markets</option>
                    @foreach($featuredMarkets as $m)
                      <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-4">
                  <select name="category" class="ref-select w-100">
                    <option value="">All Products</option>
                    @foreach($categories as $c)
                      <option value="{{ $c->slug ?? $c->id }}">{{ $c->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-4">
                  <select name="distance" class="ref-select w-100">
                    <option value="10">Within 10 km</option>
                    <option value="5">Within 5 km</option>
                    <option value="25">Within 25 km</option>
                    <option value="50">Within 50 km</option>
                  </select>
                </div>
              </div>
              <button type="submit" class="ref-btn-search">Search</button>
            </form>
          </div>
          <div class="d-flex flex-column gap-2">
            @forelse($featuredMarkets->take(3) as $idx => $m)
              @php
                $thumbStyles = [
                  ['bg' => '#E8F5E9', 'color' => '#1B5E20', 'icon' => 'bi-shop'],
                  ['bg' => '#EBF3F8', 'color' => '#236B92', 'icon' => 'bi-water'],
                  ['bg' => '#FDF4E7', 'color' => '#B37416', 'icon' => 'bi-building'],
                ];
                $curStyle = $thumbStyles[$idx % count($thumbStyles)];
              @endphp
              <div class="ref-market-list-item">
                <div class="d-flex align-items-center gap-3">
                  <div class="ref-market-thumb" style="background: {{ $curStyle['bg'] }}; color: {{ $curStyle['color'] }};">
                    <i class="bi {{ $curStyle['icon'] }}"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-dark" style="font-size:0.9rem;">{{ $m->name }}</div>
                    <div class="text-muted" style="font-size:0.76rem;">{{ $m->city ?? 'Local Area' }} &bull; {{ $m->farmers_count ?? 0 }} Farmers &bull; {{ $m->products_count ?? 0 }} Products</div>
                  </div>
                </div>
                <a href="{{ route('markets.show', $m) }}" class="ref-btn-view-market text-decoration-none">View Market &rarr;</a>
              </div>
            @empty
              <p class="text-muted small py-2 mb-0">No active markets listed yet.</p>
            @endforelse
          </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="ref-support-local-card shadow-lg border-0" style="background: url('{{ asset('images/support-local.jpg') }}') no-repeat center center / cover;">
          <div class="ref-support-local-overlay"></div>
          <div class="ref-support-local-content">
            <div class="ref-support-tag">SUPPORT LOCAL</div>
            <h4 class="ref-support-title">Better Food A Brighter Future</h4>
            <p class="ref-support-desc">Every purchase supports local farmers, healthy communities and a sustainable food system.</p>
            <a href="{{ route('about') }}" class="ref-btn-learn-more">Learn More &rarr;</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<section class="ref-section-farmers" id="farmers">
  <div class="container-xl">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-4 pb-2 gap-3">
      <div>
        <div class="ref-section-tag justify-content-start mb-2" style="margin-left:-12px;">Featured Farmers</div>
        <h2 class="ref-section-title mb-1">Meet Our Trusted Farmers</h2>
        <p class="ref-section-sub mx-0 text-start">Real people. Real produce. Supporting local, sustainable agriculture.</p>
      </div>
      <div><a href="{{ route('farmers.index') }}" class="ref-btn-view-all-farmers">View All Farmers &rarr;</a></div>
    </div>
    <div class="row g-3 g-md-4">
      @forelse($featuredFarmers as $farmer)
        <div class="col-sm-6 col-lg-3">
          <a href="{{ route('farmers.show', $farmer) }}" class="text-decoration-none">
            <div class="ref-farmer-card">
              <div class="ref-farmer-img-box">
                @if($farmer->profile_image)
                  <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}" loading="lazy">
                @else
                  <div class="w-100 h-100 d-flex align-items-center justify-content-center fw-bold" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 3rem; color: #ffffff;">
                    {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                  </div>
                @endif
                @auth
                  @if(auth()->user()->isCustomer())
                    <button class="ref-farmer-fav-btn favorite-btn {{ auth()->user()->isFollowing($farmer->id) ? 'active-fav' : '' }}" type="button" aria-label="Favourite" data-farmer-id="{{ $farmer->id }}">
                      <i class="bi bi-heart{{ auth()->user()->isFollowing($farmer->id) ? '-fill text-danger' : '' }}"></i>
                    </button>
                  @endif
                @else
                  <button class="ref-farmer-fav-btn" type="button" aria-label="Favourite" onclick="window.location='/login'">
                    <i class="bi bi-heart"></i>
                  </button>
                @endauth
              </div>
              <div class="ref-farmer-body">
                <div class="ref-farmer-name">{{ $farmer->stall_name }}</div>
                <div class="ref-farmer-category">
                  {{ $farmer->is_organic ? '🌿 Organic · ' : '' }}{{ $farmer->products_count ?? 0 }} products
                </div>
                <div class="ref-farmer-meta">
                  <div class="ref-farmer-rating">
                    <i class="bi bi-star-fill text-warning"></i>
                    {{ $farmer->average_rating ? number_format($farmer->average_rating, 1) : '—' }}
                    <span>({{ $farmer->reviews_count ?? 0 }} reviews)</span>
                  </div>
                  @if($farmer->markets->isNotEmpty())
                    <div class="ref-farmer-dist" style="font-size:0.72rem;">
                      <i class="bi bi-shop me-1"></i>{{ $farmer->markets->first()->name }}
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </a>
        </div>
      @empty
        <div class="col-12 text-center py-4 text-muted">
          <div style="font-size:3rem;opacity:0.3;">🌾</div>
          <p class="mt-2">No featured farmers yet. <a href="{{ route('farmers.index') }}" class="text-success">Browse all farmers</a></p>
        </div>
      @endforelse
    </div>
  </div>
</section>



<section class="ref-section-why">
  <div class="ref-why-bg-overlay"></div>
  <div class="container-xl position-relative" style="z-index:2;">
    <div class="text-center mb-5">
      <div class="ref-why-eyebrow">Why MarketLink</div>
      <h2 class="ref-why-title">Why Choose MarketLink?</h2>
      <p class="ref-why-sub">More than just a marketplace &mdash; we're building a healthier, more connected community.</p>
    </div>
    <div class="row g-4 text-center justify-content-center">
      <div class="col-6 col-md-3 ref-why-col">
        <div class="ref-why-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
            <path d="M11 4a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm-1.161 5.35a3.5 3.5 0 0 0 1.483.65C11.98 12.447 9.98 14 7.5 14a5.5 5.5 0 0 1 0-11c1.382 0 2.61.5 3.554 1.322A4 4 0 0 0 7.5 5.5c-2.208 0-4 1.792-4 4S5.292 13.5 7.5 13.5c1.702 0 3.162-1.069 3.746-2.587A3 3 0 0 1 9.839 9.35z"/>
            <path d="M9.5 9a2.5 2.5 0 0 1 5 0c0 1.657-2.5 5-2.5 5S9.5 10.657 9.5 9z"/>
          </svg>
        </div>
        <div class="ref-why-feature-title">Fresh &amp; Local</div>
        <p class="ref-why-feature-desc">Seasonal, high-quality produce</p>
      </div>
      <div class="col-6 col-md-3 ref-why-col">
        <div class="ref-why-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
          </svg>
        </div>
        <div class="ref-why-feature-title">Support Farmers</div>
        <p class="ref-why-feature-desc">Directly support local communities</p>
      </div>
      <div class="col-6 col-md-3 ref-why-col">
        <div class="ref-why-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
            <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
            <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
          </svg>
        </div>
        <div class="ref-why-feature-title">Easy &amp; Convenient</div>
        <p class="ref-why-feature-desc">Map, pre-order &amp; pickup</p>
      </div>
      <div class="col-6 col-md-3 ref-why-col">
        <div class="ref-why-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.033.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
          </svg>
        </div>
        <div class="ref-why-feature-title">Safe &amp; Trusted</div>
        <p class="ref-why-feature-desc">Verified farmers &amp; real reviews</p>
      </div>
    </div>
  </div>
</section>


<section class="ref-section-testimonials">
  <div class="container-xl">
    <div class="text-center mb-4 pb-2">
      <div class="ref-section-tag mb-2">What Our Customers Say</div>
      <h2 class="ref-section-title">Loved by Our Community</h2>
    </div>
    <div class="d-flex align-items-center justify-content-between gap-3">
      <button class="ref-testimonials-nav-btn d-none d-md-flex" aria-label="Previous" onclick="cycleTestimonials(-1)"><i class="bi bi-chevron-left"></i></button>
      <div class="row g-3 g-md-4 flex-grow-1" id="testimonial-cards-row">
        @php
          $staticFallbacks = [
            [
              'name' => 'Sara Ahmed',
              'role' => 'Community Member',
              'image' => asset('images/customer-1.jpg'),
              'rating' => 5,
              'comment' => 'MarketLink makes it so easy to find fresh produce from local farmers. The quality is amazing and I love supporting local!'
            ],
            [
              'name' => 'Ali Raza',
              'role' => 'Verified Shopper',
              'image' => asset('images/customer-2.jpg'),
              'rating' => 5,
              'comment' => 'The map feature and pre-order system are game changers. I always know what\'s available and where to find it.'
            ],
            [
              'name' => 'Fatima Khan',
              'role' => 'Market Visitor',
              'image' => asset('images/customer-3.jpg'),
              'rating' => 5,
              'comment' => 'Beautiful design, easy to use, and great customer service. This platform is a must for anyone who loves fresh, local food!'
            ]
          ];
          $displayReviews = [];
          if(isset($recentReviews)) {
            foreach($recentReviews as $rev) {
              $displayReviews[] = [
                'name' => $rev->customer?->user?->name ?? 'Verified Shopper',
                'role' => $rev->product ? $rev->product->name : ($rev->farmer ? $rev->farmer->stall_name : 'Customer'),
                'image' => $rev->customer?->user?->profile_image ? (str_starts_with($rev->customer->user->profile_image, 'http') ? $rev->customer->user->profile_image : asset('storage/' . $rev->customer->user->profile_image)) : null,
                'rating' => $rev->rating ?? 5,
                'comment' => $rev->comment
              ];
            }
          }
          $fbIdx = 0;
          while (count($displayReviews) < 3 && isset($staticFallbacks[$fbIdx])) {
            $displayReviews[] = $staticFallbacks[$fbIdx];
            $fbIdx++;
          }
        @endphp

        @foreach($displayReviews as $item)
          <div class="col-md-4 testimonial-card-col">
            <div class="ref-testimonial-card h-100 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex align-items-center gap-3 mb-3">
                  @if(!empty($item['image']))
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="ref-testimonial-avatar">
                  @else
                    <div class="ref-testimonial-avatar d-flex align-items-center justify-content-center fw-bold" style="background: rgba(22, 101, 52, 0.12); color: #166534; font-size: 1.1rem; border-radius: 50%; width: 48px; height: 48px; flex-shrink: 0;">
                      {{ strtoupper(substr($item['name'], 0, 1)) }}
                    </div>
                  @endif
                  <div>
                    <div class="ref-testimonial-author">{{ $item['name'] }}</div>
                    <div class="ref-testimonial-role">{{ $item['role'] }}</div>
                    <div class="ref-testimonial-stars">
                      @for($s = 1; $s <= 5; $s++)
                        <i class="bi bi-star{{ $s <= $item['rating'] ? '-fill text-warning' : ' text-muted' }}"></i>
                      @endfor
                    </div>
                  </div>
                </div>
                <p class="ref-testimonial-quote">&ldquo;{{ $item['comment'] }}&rdquo;</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <button class="ref-testimonials-nav-btn d-none d-md-flex" aria-label="Next" onclick="cycleTestimonials(1)"><i class="bi bi-chevron-right"></i></button>
    </div>
    <div class="ref-testimonial-dots">
      <span class="ref-dot" onclick="setTestimonialDot(0)"></span>
      <span class="ref-dot active" onclick="setTestimonialDot(1)"></span>
      <span class="ref-dot" onclick="setTestimonialDot(2)"></span>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapEl = document.getElementById('ref-leaflet-map');
  if (mapEl) {
@php
  $mapMarkets = $featuredMarkets->map(function($m) {
    return [
      'name' => $m->name,
      'lat' => (float)$m->latitude,
      'lng' => (float)$m->longitude,
      'products' => $m->products_count ?? 0,
      'farmers' => $m->farmers_count ?? 0,
      'city' => $m->city ?? 'Local',
      'url' => route('markets.show', $m)
    ];
  });
@endphp
    const dbMarkets = @json($mapMarkets);

    const defaultLat = dbMarkets.length > 0 && dbMarkets[0].lat ? dbMarkets[0].lat : 31.5204;
    const defaultLng = dbMarkets.length > 0 && dbMarkets[0].lng ? dbMarkets[0].lng : 74.3587;

    const map = L.map('ref-leaflet-map', { zoomControl: true, scrollWheelZoom: false }).setView([defaultLat, defaultLng], 12);
    L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
      attribution: '&copy; Google Maps'
    }).addTo(map);

    function pin() {
      return L.divIcon({ className: '', html: '<div class="ref-marker-pin"><span>🌿</span></div>', iconSize: [32,32], iconAnchor: [16,32] });
    }

    if (dbMarkets.length > 0) {
      dbMarkets.forEach(function(loc) {
        if (loc.lat && loc.lng) {
          L.marker([loc.lat, loc.lng], { icon: pin() }).addTo(map)
            .bindPopup('<div style="font-family:sans-serif;padding:6px;min-width:140px;"><strong style="color:#123C2F;font-size:0.9rem;">' + loc.name + '</strong><br><small class="text-muted">' + loc.city + ' &bull; ' + loc.farmers + ' Farmers &bull; ' + loc.products + ' Products</small><br><a href="' + loc.url + '" style="color:#166534;font-size:0.75rem;font-weight:600;text-decoration:none;display:inline-block;margin-top:4px;">Visit Market &rarr;</a></div>');
        }
      });
    }
  }
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function(e) {
      const t = document.querySelector(this.getAttribute('href'));
      if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
    });
  });

  // Video Sound Toggle
  const soundToggle = document.getElementById('heroSoundToggle');
  const videoBg = document.getElementById('heroVideoBg');
  const soundIcon = document.getElementById('heroSoundIcon');
  const soundLabel = document.getElementById('heroSoundLabel');
  if (soundToggle && videoBg) {
    soundToggle.addEventListener('click', function() {
      videoBg.muted = !videoBg.muted;
      if (videoBg.muted) {
        soundIcon.className = 'bi bi-volume-mute-fill';
        if (soundLabel) soundLabel.textContent = 'Sound';
      } else {
        soundIcon.className = 'bi bi-volume-up-fill text-success';
        if (soundLabel) soundLabel.textContent = 'Mute';
      }
    });
  }
});

function toggleFav(btn) {
  const icon = btn.querySelector('i');
  if (icon.classList.contains('bi-heart')) {
    icon.classList.replace('bi-heart','bi-heart-fill');
    btn.style.color = '#E24B4A';
    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Added to favourites!', showConfirmButton:false, timer:1800 });
  } else {
    icon.classList.replace('bi-heart-fill','bi-heart');
    btn.style.color = '#4B5E54';
  }
}

let activeTestimonialIdx = 1;
function setTestimonialDot(idx) {
  activeTestimonialIdx = idx;
  document.querySelectorAll('.ref-dot').forEach((d,i) => d.classList.toggle('active', i===idx));
}
function cycleTestimonials(dir) {
  const dots = document.querySelectorAll('.ref-dot');
  activeTestimonialIdx = (activeTestimonialIdx + dir + dots.length) % dots.length;
  setTestimonialDot(activeTestimonialIdx);
}
</script>
@endpush
