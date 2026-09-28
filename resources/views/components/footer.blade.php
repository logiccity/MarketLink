<footer class="pf-footer" id="site-footer" aria-label="Site Footer">

  {{-- ===== Newsletter Banner ===== --}}
  <div class="pf-newsletter-strip">
    <div class="container-xl">
      <div class="row align-items-center gy-4">
        <div class="col-lg-6">
          <div class="pf-nl-eyebrow">
            <i class="bi bi-envelope-heart-fill me-2"></i>Stay in Season
          </div>
          <h3 class="pf-nl-heading">Get weekly harvest updates<br>direct from local farms.</h3>
          <p class="pf-nl-sub">No spam. Unsubscribe anytime. Pure local goodness in your inbox.</p>
        </div>
        <div class="col-lg-6">
          <form class="pf-nl-form" onsubmit="return false;">
            <div class="pf-nl-input-group">
              <input type="email" class="pf-nl-input" placeholder="Your email address" aria-label="Newsletter email">
              <button type="submit" class="pf-nl-btn">
                <i class="bi bi-send-fill me-1"></i> Subscribe
              </button>
            </div>
            <p class="pf-nl-privacy mt-2">
              <i class="bi bi-shield-lock me-1"></i> We respect your privacy. Zero spam, ever.
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Main Footer Body ===== --}}
  <div class="pf-body">
    <div class="container-xl">
      <div class="row gy-5 py-5">

        {{-- Column 1: Brand --}}
        <div class="col-lg-4 col-md-6">
          <a href="{{ route('home') }}" class="d-inline-block mb-4 text-decoration-none">
            <img src="{{ asset('images/logo.png') }}" alt="MarketLink Logo" class="pf-logo">
          </a>
          <p class="pf-brand-desc">
            MarketLink connects you directly with verified regional farmers at your local weekend market. Reserve fresh, seasonal produce before market day—no delivery fees, no middlemen, just pure local harvest.
          </p>
          {{-- Trust Badges --}}
          <div class="pf-trust-row">
            <div class="pf-trust-badge">
              <i class="bi bi-shield-check-fill"></i>
              <span>Verified Farmers</span>
            </div>
            <div class="pf-trust-badge">
              <i class="bi bi-cash-coin"></i>
              <span>Cash at Pickup</span>
            </div>
            <div class="pf-trust-badge">
              <i class="bi bi-geo-alt-fill"></i>
              <span>Local Markets</span>
            </div>
          </div>
        </div>

        {{-- Column 2: Quick Links --}}
        <div class="col-lg-2 col-md-3 col-6">
          <h6 class="pf-col-heading">Quick Links</h6>
          <ul class="pf-link-list">
            <li><a href="{{ route('home') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Home</a></li>
            <li><a href="{{ route('markets.index') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Markets</a></li>
            <li><a href="{{ route('farmers.index') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Farmers</a></li>
            <li><a href="{{ route('products.index') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Products</a></li>
            <li><a href="{{ route('about') }}" class="pf-link"><i class="bi bi-chevron-right"></i> About Us</a></li>
            <li><a href="{{ route('faq') }}" class="pf-link"><i class="bi bi-chevron-right"></i> FAQ</a></li>
            <li><a href="{{ route('contact') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Contact</a></li>
          </ul>
        </div>

        {{-- Column 3: Explore --}}
        <div class="col-lg-2 col-md-3 col-6">
          <h6 class="pf-col-heading">Explore</h6>
          <ul class="pf-link-list">
            <li><a href="{{ route('register') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Join as Farmer</a></li>
            <li><a href="{{ route('register') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Register</a></li>
            <li><a href="{{ route('login') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Sign In</a></li>
            <li><a href="{{ route('products.index') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Browse Produce</a></li>
            <li><a href="{{ route('markets.index') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Find a Market</a></li>
            <li><a href="{{ route('contact') }}" class="pf-link"><i class="bi bi-chevron-right"></i> Support</a></li>
          </ul>
        </div>

        {{-- Column 4: Contact & Social --}}
        <div class="col-lg-4 col-md-6">
          <h6 class="pf-col-heading">Get in Touch</h6>
          <ul class="pf-contact-list">
            <li>
              <div class="pf-contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
              <span>123 Harvest Road, Green Valley Market District, GV 4500</span>
            </li>
            <li>
              <div class="pf-contact-icon"><i class="bi bi-telephone-fill"></i></div>
              <span>+1 (800) 555-FARM</span>
            </li>
            <li>
              <div class="pf-contact-icon"><i class="bi bi-envelope-fill"></i></div>
              <span>hello@egreenbasket.com</span>
            </li>
            <li>
              <div class="pf-contact-icon"><i class="bi bi-clock-fill"></i></div>
              <span>Market Hours: Sat–Sun, 6:00 AM – 2:00 PM</span>
            </li>
          </ul>

          {{-- Social Icons --}}
          <div class="mt-4">
            <p class="pf-col-heading mb-3">Follow Us</p>
            <div class="pf-socials">
              <a href="#" class="pf-social-btn" aria-label="Facebook">
                <i class="bi bi-facebook"></i>
              </a>
              <a href="#" class="pf-social-btn" aria-label="Instagram">
                <i class="bi bi-instagram"></i>
              </a>
              <a href="#" class="pf-social-btn" aria-label="X / Twitter">
                <i class="bi bi-twitter-x"></i>
              </a>
              <a href="#" class="pf-social-btn" aria-label="YouTube">
                <i class="bi bi-youtube"></i>
              </a>
              <a href="#" class="pf-social-btn" aria-label="TikTok">
                <i class="bi bi-tiktok"></i>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- ===== Footer Divider ===== --}}
  <div class="pf-divider-wrap">
    <div class="container-xl"><hr class="pf-divider"></div>
  </div>

  {{-- ===== Bottom Bar ===== --}}
  <div class="pf-bottom-bar">
    <div class="container-xl">
      <div class="row align-items-center gy-3">

        <div class="col-md-5 text-center text-md-start">
          <span class="pf-copy">
            &copy; {{ date('Y') }} <strong>MarketLink</strong> by eGreen Basket. All rights reserved.
          </span>
        </div>

        <div class="col-md-4 text-center">
          <div class="pf-bottom-tags">
            <span><i class="bi bi-leaf-fill me-1 text-success"></i>Fresh Produce</span>
            <span class="pf-tag-dot">·</span>
            <span><i class="bi bi-people-fill me-1 text-warning"></i>Local Farmers</span>
            <span class="pf-tag-dot">·</span>
            <span><i class="bi bi-heart-fill me-1 text-danger"></i>Communities</span>
          </div>
        </div>

        <div class="col-md-3 text-center text-md-end">
          <div class="pf-bottom-links">
            <a href="{{ route('contact') }}" class="pf-bottom-link">Privacy</a>
            <span class="pf-tag-dot">·</span>
            <a href="{{ route('contact') }}" class="pf-bottom-link">Terms</a>
            <span class="pf-tag-dot">·</span>
            <a href="{{ route('faq') }}" class="pf-bottom-link">FAQ</a>
          </div>
        </div>

      </div>
    </div>
  </div>

</footer>
