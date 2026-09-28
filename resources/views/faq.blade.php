@extends('layouts.app')

@section('title', 'Frequently Asked Questions — MarketLink Concierge')
@section('meta_description', 'Find instant answers regarding seasonal harvest pre-orders, collection schedules, PKR cash stall settlements, and grower onboarding on MarketLink.')

@section('content')
{{-- Hero Banner --}}
<section class="page-hero-banner py-5 py-lg-6" style="background-image: url('{{ asset('images/faq-hero-support.jpg') }}');">
  <div class="container hero-content-rel text-center" style="max-width: 860px;">
    
    <div class="d-inline-flex mb-3">
      <span class="hero-tag-badge">
        <i class="bi bi-patch-question text-warning"></i>
        <span>Knowledge Base &bull; Community Guidance</span>
      </span>
    </div>

    <h1 class="display-5 fw-bold text-white mb-3" style="font-family: var(--font-serif); letter-spacing: -0.02em;">
      How Can We <span style="color: #D4B477;">Help You Today?</span>
    </h1>

    <p class="lead text-white-50 mx-auto mb-4" style="font-size: 1.1rem; line-height: 1.7; max-width: 660px;">
      Find immediate answers regarding dawn-harvest pre-orders, market collection windows, cash payments in PKR at stalls, and grower onboarding.
    </p>

    {{-- Interactive Live Search Box --}}
    <div class="mx-auto position-relative" style="max-width: 620px;">
      <div class="d-flex align-items-center bg-white rounded-pill shadow-lg p-2 pe-3" style="border: 2px solid rgba(255,255,255,0.3); backdrop-filter: blur(8px);">
        <i class="bi bi-search text-success fs-5 ms-3 me-2"></i>
        <input type="text" id="faqSearchInput" class="form-control border-0 shadow-none px-2 text-dark" placeholder="Search questions: e.g. pickup, cash, cutoff, organic, cancel..." style="font-size: 0.95rem; background: transparent;">
        <button type="button" id="faqSearchClear" class="btn btn-sm btn-link text-muted d-none p-0 text-decoration-none" title="Clear search">
          <i class="bi bi-x-circle-fill fs-5"></i>
        </button>
      </div>

      {{-- Quick Search Tags --}}
      <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mt-3 text-white-50 small" style="font-size: 0.8rem;">
        <span class="opacity-75">Popular:</span>
        <a href="javascript:void(0)" class="quick-tag text-white-50 text-decoration-none bg-white-10 px-2 py-0.5 rounded-pill" onclick="searchTag('pickup')">Pickup</a>
        <a href="javascript:void(0)" class="quick-tag text-white-50 text-decoration-none bg-white-10 px-2 py-0.5 rounded-pill" onclick="searchTag('cash')">Cash in PKR</a>
        <a href="javascript:void(0)" class="quick-tag text-white-50 text-decoration-none bg-white-10 px-2 py-0.5 rounded-pill" onclick="searchTag('cancel')">Cancel Order</a>
        <a href="javascript:void(0)" class="quick-tag text-white-50 text-decoration-none bg-white-10 px-2 py-0.5 rounded-pill" onclick="searchTag('organic')">Organic</a>
        <a href="javascript:void(0)" class="quick-tag text-white-50 text-decoration-none bg-white-10 px-2 py-0.5 rounded-pill" onclick="searchTag('grower')">Become a Grower</a>
      </div>
    </div>

  </div>
</section>

{{-- Main FAQ Content Area --}}
<section class="py-5 py-lg-6" style="background: var(--color-bg);">
  <div class="container" style="max-width: 960px;">

    {{-- Category Filter Pills & Expand Toggle --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2">
      
      {{-- Filter Pills --}}
      <div class="d-flex flex-wrap gap-2" id="faqCategoryFilter">
        <button type="button" class="faq-cat-pill active" data-category="all">
          <i class="bi bi-grid-fill"></i> All Topics
        </button>
        <button type="button" class="faq-cat-pill" data-category="preorder">
          <i class="bi bi-basket2"></i> Pre-Orders &amp; Pickup
        </button>
        <button type="button" class="faq-cat-pill" data-category="payment">
          <i class="bi bi-cash-stack"></i> Cash &amp; Payment (PKR)
        </button>
        <button type="button" class="faq-cat-pill" data-category="growers">
          <i class="bi bi-shop"></i> For Farmers &amp; Stalls
        </button>
        <button type="button" class="faq-cat-pill" data-category="quality">
          <i class="bi bi-flower1"></i> Quality &amp; Organic
        </button>
      </div>

      {{-- Expand/Collapse Toggle --}}
      <div>
        <button type="button" id="toggleAllFaq" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
          <i class="bi bi-arrows-expand"></i><span>Expand All</span>
        </button>
      </div>

    </div>

    {{-- Empty State for Search --}}
    <div id="faqNoResults" class="d-none text-center py-5 my-4 bg-white rounded-4 border shadow-sm p-4">
      <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: var(--color-sage-soft); color: var(--color-primary); font-size: 1.8rem;">
        <i class="bi bi-search"></i>
      </div>
      <h5 class="fw-bold text-dark mb-2" style="font-family: var(--font-serif); font-size: 1.3rem;">No Matching Questions Found</h5>
      <p class="text-muted small mb-4 mx-auto" style="max-width: 480px; line-height: 1.6;">
        We couldn't find any questions matching your query. Try a different keyword or contact our community concierge desk directly.
      </p>
      <button type="button" id="resetFaqSearch" class="btn btn-lux-primary btn-sm rounded-pill px-4 py-2 fw-bold">
        <i class="bi bi-arrow-clockwise me-1"></i> Reset Search
      </button>
    </div>

    {{-- Accordion Container --}}
    <div class="accordion faq-accordion d-flex flex-column gap-3" id="faqAccordion">

      <div class="accordion-item faq-item" data-category="preorder">
        <h2 class="accordion-header" id="faq1-heading">
          <button class="accordion-button fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; font-weight: 700;">PRE-ORDER</span>
            How does pre-ordering work on MarketLink?
          </button>
        </h2>
        <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Browse seasonal harvest offerings from local growers, choose your designated market venue and preferred pickup window, and reserve your produce online. <strong>Zero upfront payment is charged online</strong>. On market day, you visit your producer's market booth, inspect your dawn-harvested crate, and settle in Pakistani Rupee (PKR) cash directly with the grower.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="payment">
        <h2 class="accordion-header" id="faq2-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(201, 168, 106, 0.15); color: #8C6B2D; font-size: 0.72rem; font-weight: 700;">PAYMENT</span>
            What currency is accepted and when do I pay?
          </button>
        </h2>
        <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            All prices on MarketLink are quoted in <strong>Pakistani Rupee (PKR)</strong>. Payment is handled <strong>in cash directly to the grower at their stall upon collection</strong>. There are no credit card processing fees, transaction markups, or digital wallet commissions deducted from patrons or growers. Please bring exact or small change when possible!
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="preorder">
        <h2 class="accordion-header" id="faq3-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; font-weight: 700;">PICKUP</span>
            Is home delivery or courier shipping available?
          </button>
        </h2>
        <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            <strong>No, MarketLink is strictly a community market stall pickup platform.</strong> By eliminating delivery couriers, motor fuel emissions, and single-use delivery plastics, 100% of your food spend supports local family growers, and your greens are received within hours of picking rather than days in transit.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="preorder">
        <h2 class="accordion-header" id="faq4-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; font-weight: 700;">PRE-ORDER</span>
            Can I cancel or modify my reservation?
          </button>
        </h2>
        <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Yes. You may cancel your pre-order directly within your <strong>Customer Portal</strong> up until the producer's specified order cutoff deadline (typically 12 to 24 hours prior to market opening). Once cutoff passes, growers begin early morning harvesting specifically for your items.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="preorder">
        <h2 class="accordion-header" id="faq5-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; font-weight: 700;">LOGISTICS</span>
            What happens if I cannot collect during my pickup window?
          </button>
        </h2>
        <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            If you encounter delays on market morning, contact your grower immediately via the stall contact information provided on your order confirmation page. Most farmers will gladly hold your pre-packed crate until market closing. Unclaimed items after market hours may be offered to other walk-up market patrons.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="growers">
        <h2 class="accordion-header" id="faq6-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(217, 119, 6, 0.1); color: #b45309; font-size: 0.72rem; font-weight: 700;">GROWERS</span>
            How does an independent grower enroll their farm stall?
          </button>
        </h2>
        <div id="faq6" class="accordion-collapse collapse" aria-labelledby="faq6-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Click <strong>"Enroll as Grower"</strong> on the navigation menu or visit the registration portal. Submit your farm name, primary contact person, farming philosophy, operating days, and the participating market hubs you attend. Our administrative curation team reviews and activates approved grower stalls within 24 hours.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="growers">
        <h2 class="accordion-header" id="faq7-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(217, 119, 6, 0.1); color: #b45309; font-size: 0.72rem; font-weight: 700;">COMMISSIONS</span>
            Are there listing fees or platform commissions for growers?
          </button>
        </h2>
        <div id="faq7" class="accordion-collapse collapse" aria-labelledby="faq7-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            No. MarketLink operates on a transparent community empowerment model. Because transactions are settled directly in cash between the patron and producer at the market stall, <strong>producers keep 100% of their earnings</strong>. There are no percentage rake-offs or recurring listing fees.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="payment">
        <h2 class="accordion-header" id="faq8-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(201, 168, 106, 0.15); color: #8C6B2D; font-size: 0.72rem; font-weight: 700;">PAYMENT</span>
            Can I pay via digital mobile transfer (e.g. Raast/EasyPaisa) at the stall?
          </button>
        </h2>
        <div id="faq8" class="accordion-collapse collapse" aria-labelledby="faq8-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            While MarketLink specifies physical cash at the stall as the universal standard, many producers also maintain their personal QR codes or account numbers (such as Raast or mobile wallets) directly at their stall booth. You may inquire with your grower upon collection if mobile transfer is mutually convenient.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="quality">
        <h2 class="accordion-header" id="faq9-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 0.72rem; font-weight: 700;">ORGANIC</span>
            How do I know if produce is certified organic?
          </button>
        </h2>
        <div id="faq9" class="accordion-collapse collapse" aria-labelledby="faq9-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Products grown organically feature a green <strong>"🌿 100% Organic"</strong> badge on product cards and detail pages. In addition, grower stall profiles clearly display whether the producer practices certified organic, pesticide-free, or regenerative bio-intensive agriculture.
          </div>
        </div>
      </div>

      <div class="accordion-item faq-item" data-category="quality">
        <h2 class="accordion-header" id="faq10-heading">
          <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false" aria-controls="faq10">
            <span class="badge rounded-pill me-3 px-2 py-1" style="background: rgba(20, 83, 45, 0.08); color: var(--color-primary); font-size: 0.72rem; font-weight: 700;">FEEDBACK</span>
            How do patron reviews and ratings work?
          </button>
        </h2>
        <div id="faq10" class="accordion-collapse collapse" aria-labelledby="faq10-heading" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Only verified customers who have placed a reservation can submit reviews for products and growers. Once your order status is marked fulfilled upon pickup, a feedback prompt appears in your Patron Dashboard allowing you to rate the harvest from 1 to 5 stars and share helpful comments for fellow community members.
          </div>
        </div>
      </div>

    </div>

    {{-- Bottom Assistance Cards --}}
    <div class="row g-4 mt-5">
      
      {{-- Card 1: Concierge Support --}}
      <div class="col-md-6">
        <div class="card border-0 rounded-4 p-4 shadow-sm h-100 d-flex flex-column" style="background: #ffffff; border: 1px solid var(--color-border-subtle) !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: var(--color-sage-soft); color: var(--color-primary); font-size: 1.25rem;">
              <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0" style="font-family: var(--font-serif); font-size: 1.15rem;">Still Have Questions?</h5>
              <div class="text-muted small">Our community support desk is ready to assist.</div>
            </div>
          </div>
          <p class="text-muted small mb-4" style="line-height: 1.65;">
            Have a custom order requirement, question about a community market venue, or feedback? Send our team an inquiry anytime.
          </p>
          <a href="{{ route('contact') }}" class="btn btn-lux-primary btn-sm rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 mt-auto" style="width: fit-content;">
            <i class="bi bi-envelope-fill"></i> Contact Concierge Desk
          </a>
        </div>
      </div>

      {{-- Card 2: Grower Onboarding --}}
      <div class="col-md-6">
        <div class="card border-0 rounded-4 p-4 shadow-sm h-100 d-flex flex-column" style="background: #ffffff; border: 1px solid var(--color-border-subtle) !important;">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(201, 168, 106, 0.15); color: #8C6B2D; font-size: 1.25rem;">
              <i class="bi bi-shop"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0" style="font-family: var(--font-serif); font-size: 1.15rem;">Are You A Regional Grower?</h5>
              <div class="text-muted small">Join our network of market stalls.</div>
            </div>
          </div>
          <p class="text-muted small mb-4" style="line-height: 1.65;">
            Empower your farm with weekly advance reservations, reduced harvest waste, fair farmgate revenue, and direct patron relationships.
          </p>
          <a href="{{ route('register.farmer') }}" class="btn btn-lux-secondary btn-sm rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 mt-auto" style="width: fit-content;">
            <i class="bi bi-check2-circle"></i> Enroll as a Grower
          </a>
        </div>
      </div>

    </div>

  </div>
</section>

{{-- Client-side Filter & Search Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('faqSearchInput');
  const clearBtn = document.getElementById('faqSearchClear');
  const categoryBtns = document.querySelectorAll('#faqCategoryFilter .faq-cat-pill');
  const faqItems = document.querySelectorAll('.faq-item');
  const noResults = document.getElementById('faqNoResults');
  const resetBtn = document.getElementById('resetFaqSearch');
  const toggleAllBtn = document.getElementById('toggleAllFaq');

  let activeCategory = 'all';
  let searchQuery = '';

  function filterFaqs() {
    let visibleCount = 0;
    const query = searchQuery.trim().toLowerCase();

    faqItems.forEach(item => {
      const category = item.getAttribute('data-category');
      const text = item.textContent.toLowerCase();

      const matchesCat = (activeCategory === 'all' || category === activeCategory);
      const matchesSearch = (!query || text.includes(query));

      if (matchesCat && matchesSearch) {
        item.classList.remove('d-none');
        visibleCount++;
      } else {
        item.classList.add('d-none');
      }
    });

    if (visibleCount === 0) {
      noResults.classList.remove('d-none');
    } else {
      noResults.classList.add('d-none');
    }
  }

 
  window.searchTag = function (tag) {
    if (searchInput) {
      searchInput.value = tag;
      searchQuery = tag;
      clearBtn.classList.remove('d-none');
      searchInput.focus();
      filterFaqs();
    }
  };

  categoryBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      categoryBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      activeCategory = this.getAttribute('data-category');
      filterFaqs();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', function () {
      searchQuery = this.value;
      if (searchQuery.length > 0) {
        clearBtn.classList.remove('d-none');
      } else {
        clearBtn.classList.add('d-none');
      }
      filterFaqs();
    });
  }

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      searchInput.value = '';
      searchQuery = '';
      clearBtn.classList.add('d-none');
      searchInput.focus();
      filterFaqs();
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      searchInput.value = '';
      searchQuery = '';
      clearBtn.classList.add('d-none');
      const allBtn = document.querySelector('#faqCategoryFilter button[data-category="all"]');
      if (allBtn) allBtn.click();
      filterFaqs();
    });
  }

  let isAllExpanded = false;
  if (toggleAllBtn) {
    toggleAllBtn.addEventListener('click', function () {
      isAllExpanded = !isAllExpanded;
      const collapses = document.querySelectorAll('#faqAccordion .accordion-collapse');
      collapses.forEach(el => {
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(el);
        if (isAllExpanded) {
          bsCollapse.show();
        } else {
          bsCollapse.hide();
        }
      });
      const icon = toggleAllBtn.querySelector('i');
      const text = toggleAllBtn.querySelector('span');
      if (isAllExpanded) {
        icon.className = 'bi bi-arrows-collapse me-1';
        text.textContent = 'Collapse All';
      } else {
        icon.className = 'bi bi-arrows-expand me-1';
        text.textContent = 'Expand All';
      }
    });
  }
});
</script>

{{-- JSON-LD FAQPage Schema for Search Engines --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How does pre-ordering work on MarketLink?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Browse seasonal harvest offerings from local growers, choose your designated market venue and preferred pickup window, and reserve your produce online. Zero upfront payment is charged online. On market day, you visit your producer's market booth and settle in Pakistani Rupee (PKR) cash directly with the grower."
      }
    },
    {
      "@type": "Question",
      "name": "What currency is accepted and when do I pay?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "All prices on MarketLink are quoted in Pakistani Rupee (PKR). Payment is handled in cash directly to the grower at their stall upon collection. There are no credit card processing fees or commissions."
      }
    },
    {
      "@type": "Question",
      "name": "Is home delivery or courier shipping available?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "No, MarketLink is strictly a community market stall pickup platform. 100% of your food spend supports local family growers with zero courier emissions."
      }
    },
    {
      "@type": "Question",
      "name": "Can I cancel or modify my reservation?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes. You may cancel your pre-order directly within your Customer Portal up until the producer's specified order cutoff deadline."
      }
    },
    {
      "@type": "Question",
      "name": "How does an independent grower enroll their farm stall?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Select 'Enroll as Grower' on the navigation menu. Submit farm name, primary contact, farming philosophy, operating days, and market hubs. Admin curation reviews and activates stalls within 24 hours."
      }
    }
  ]
}
</script>
@endsection
