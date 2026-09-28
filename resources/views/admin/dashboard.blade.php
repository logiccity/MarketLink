@extends('layouts.admin')

@section('title', 'Executive Operations Dashboard — MarketLink')
@section('page-title', 'MarketLink Operations Hub')

@section('content')

{{-- Precision Luxury KPI Cards Grid --}}
<div class="dashboard-kpi-grid">

  {{-- KPI: Gross Revenue --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #16845B, #20A66A);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Gross Volume (GMV)</div>
        <div class="kpi-value" style="color: var(--admin-emerald);">
          <span class="kpi-currency">PKR</span> {{ number_format($stats['revenue'], 2) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(22, 132, 91, 0.1); color: var(--admin-emerald);">
        <i class="bi bi-cash-stack"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <i class="bi bi-shield-check text-success"></i>
      <span>All-Time: <strong>PKR {{ number_format($stats['revenue_all_time'], 2) }}</strong></span>
    </div>
  </div>

  {{-- KPI: Total Pre-Orders --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #2563EB, #60A5FA);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Harvest Pre-Orders</div>
        <div class="kpi-value" style="color: #1D4ED8;">
          {{ number_format($stats['orders']) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(37, 99, 235, 0.1); color: #2563EB;">
        <i class="bi bi-receipt"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <span class="text-primary fw-bold">{{ $stats['completed_orders'] }}</span> fulfilled &bull;
      <span class="text-warning fw-bold">{{ $stats['pending_orders'] }}</span> awaiting pickup
    </div>
  </div>

  {{-- KPI: Active Growers --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #D4B477, #E5C992);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Active Producers</div>
        <div class="kpi-value" style="color: #8C6B2D;">
          {{ number_format($stats['farmers']) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(212, 180, 119, 0.15); color: #8C6B2D;">
        <i class="bi bi-person-badge"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      @if($stats['pending_farmers'] > 0)
        <span class="badge rounded-pill" style="background: var(--admin-warning-soft); color: #925D07; font-weight: 700;">
          {{ $stats['pending_farmers'] }} pending review
        </span>
      @else
        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>All stalls curated</span>
      @endif
    </div>
  </div>

  {{-- KPI: Patron Community --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #059669, #34D399);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Patron Community</div>
        <div class="kpi-value" style="color: #047857;">
          {{ number_format($stats['customers']) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(5, 150, 105, 0.1); color: #059669;">
        <i class="bi bi-people-fill"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <i class="bi bi-person-plus text-success"></i>
      <span>{{ $stats['new_customers'] }} enrolled in this interval</span>
    </div>
  </div>

  {{-- KPI: Market Hubs --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #7C3AED, #A78BFA);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Community Hubs</div>
        <div class="kpi-value" style="color: #6D28D9;">
          {{ number_format($stats['markets']) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED;">
        <i class="bi bi-shop-window"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <span class="text-muted">{{ $stats['active_markets'] }} active market venues</span>
    </div>
  </div>

  {{-- KPI: Harvest Offerings --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #0D9488, #2DD4BF);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Heritage Varieties</div>
        <div class="kpi-value" style="color: #0F766E;">
          {{ number_format($stats['products']) }}
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(13, 148, 136, 0.1); color: #0D9488;">
        <i class="bi bi-box-seam"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <span class="text-muted">{{ $stats['active_products'] }} currently harvest-ready</span>
    </div>
  </div>

  {{-- KPI: Fulfillment Rate --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #10B981, #059669);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Fulfillment Rate</div>
        <div class="kpi-value" style="color: #059669;">
          @php
            $rate = $stats['orders'] > 0 ? round(($stats['completed_orders'] / $stats['orders']) * 100, 1) : 100;
          @endphp
          {{ $rate }}%
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
        <i class="bi bi-check2-circle"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <span class="text-muted">Direct stall collection completion</span>
    </div>
  </div>

  {{-- KPI: Patron Rating --}}
  <div class="kpi-card-lux" style="--admin-card-accent: linear-gradient(90deg, #F59E0B, #D97706);">
    <div class="kpi-header">
      <div>
        <div class="kpi-label">Patron Satisfaction</div>
        <div class="kpi-value" style="color: #B45309;">
          {{ number_format($stats['avg_rating'], 1) }} ★
        </div>
      </div>
      <div class="kpi-icon-wrap" style="background: rgba(245, 158, 11, 0.12); color: #D97706;">
        <i class="bi bi-star-fill"></i>
      </div>
    </div>
    <div class="kpi-subtext">
      <span class="text-muted">From <strong>{{ $stats['reviews_count'] }}</strong> verified reviews</span>
    </div>
  </div>

</div>

{{-- Analytics & Trend Visualization Area --}}
<div class="row g-4 mb-4">
  
  {{-- Main Chart: Volume & Revenue --}}
  <div class="col-lg-8">
    <div class="portal-card-lux h-100">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-graph-up-arrow text-success"></i> Order Velocity & Gross Revenue
          </h3>
          <div class="portal-card-subtitle-lux">Daily reservation volume and circulating community value in Pakistani Rupee (PKR)</div>
        </div>
        <div>
          <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-lux-outline">
            Full Analytics <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
      <div class="portal-card-body-lux">
        <div style="height: 290px; position: relative;" class="analytics-chart-container">
          <canvas id="luxuryAnalyticsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- Secondary Chart: Status Breakdown Donut --}}
  <div class="col-lg-4">
    <div class="portal-card-lux h-100">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-pie-chart text-warning"></i> Order Pipeline
          </h3>
          <div class="portal-card-subtitle-lux">Current status distribution</div>
        </div>
      </div>
      <div class="portal-card-body-lux d-flex flex-column align-items-center justify-content-center">
        <div style="height: 200px; width: 200px; position: relative;" class="mb-3 donut-chart-container">
          <canvas id="luxuryDonutChart"></canvas>
        </div>
        <div class="w-100 pt-2 border-top">
          <div class="row g-2 text-center" style="font-size: 0.78rem;">
            <div class="col-4">
              <span class="d-block text-muted">Placed</span>
              <strong class="text-primary">{{ $chartData['statusBreakdown']['Placed'] ?? 0 }}</strong>
            </div>
            <div class="col-4">
              <span class="d-block text-muted">Ready</span>
              <strong style="color: #7E22CE;">{{ $chartData['statusBreakdown']['Ready'] ?? 0 }}</strong>
            </div>
            <div class="col-4">
              <span class="d-block text-muted">Completed</span>
              <strong class="text-success">{{ $chartData['statusBreakdown']['Completed'] ?? 0 }}</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- Operations Core --}}
<div class="row g-4">

  {{-- LEFT COLUMN: Orders + Pending Farmers + Top Products --}}
  <div class="col-lg-8 d-flex flex-column gap-4">

    {{-- Recent Pre-Orders Table --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-receipt-cutoff text-success"></i> Recent Harvest Pre-Orders
          </h3>
          <div class="portal-card-subtitle-lux">Real-time patron reservations across local market venues</div>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-lux-outline">
          View All Orders ({{ $stats['total_orders'] }})
        </a>
      </div>

      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Grower / Stall</th>
              <th>Market Venue</th>
              <th>Amount</th>
              <th>Status</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $order)
              <tr>
                <td>
                  <a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none" style="color: var(--admin-emerald); font-family: var(--font-admin-heading); font-size: 0.95rem;">
                    #{{ $order->order_number }}
                  </a>
                  <div class="text-muted small" style="font-size: 0.72rem;">{{ $order->created_at->format('M j, H:i') }}</div>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $order->customer->user->name ?? 'Patron' }}</div>
                  <div class="text-muted small" style="font-size: 0.72rem;">{{ $order->customer->user->email ?? '—' }}</div>
                </td>
                <td>
                  <div class="fw-semibold text-dark">{{ $order->farmer->stall_name ?? '—' }}</div>
                  <div class="text-muted small" style="font-size: 0.72rem;">{{ $order->farmer->contact_person ?? '' }}</div>
                </td>
                <td>
                  <span class="badge rounded-pill bg-light text-secondary border px-2 py-1" style="font-size: 0.74rem;">
                    📍 {{ $order->market->name ?? 'Market' }}
                  </span>
                </td>
                <td>
                  <span class="fw-bold" style="color: var(--admin-emerald); font-size: 0.92rem;">
                    PKR {{ number_format($order->total, 2) }}
                  </span>
                </td>
                <td>
                  @php
                    $statusStyles = [
                      'PLACED'            => 'badge-lux-placed',
                      'ACCEPTED'          => 'badge-lux-accepted',
                      'READY_FOR_PICKUP'  => 'badge-lux-ready',
                      'COMPLETED'         => 'badge-lux-completed',
                      'CANCELLED'         => 'badge-lux-cancelled',
                      'DECLINED'          => 'badge-lux-cancelled',
                    ];
                    $badgeClass = $statusStyles[$order->status] ?? 'badge-lux-placed';
                  @endphp
                  <span class="badge-lux {{ $badgeClass }}">
                    {{ str_replace('_', ' ', $order->status) }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.orders.show', $order) }}" class="btn-lux-icon" title="View Order Voucher">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted small">No pre-orders recorded yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pending Producer Applications --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-hourglass-split text-warning"></i> Pending Grower Applications
          </h3>
          <div class="portal-card-subtitle-lux">Stall registration submissions awaiting administrative accreditation</div>
        </div>
        <a href="{{ route('admin.farmers.index', ['status' => 'pending']) }}" class="small text-success text-decoration-none fw-semibold">
          All Applications
        </a>
      </div>

      <div class="portal-card-body-lux d-flex flex-column gap-3">
        @forelse($pendingFarmers as $farmer)
          <div class="pending-farmer-item p-3 rounded-4 d-flex align-items-center justify-content-between gap-3 flex-wrap" style="background: #FBFDFB; border: 1px solid var(--admin-border-subtle);">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-3 overflow-hidden shadow-sm flex-shrink-0" style="width: 50px; height: 50px; border: 1.5px solid var(--admin-border);">
                @if($farmer->profile_image)
                  <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold" style="background: linear-gradient(135deg, var(--admin-forest), var(--admin-emerald)); font-size: 1.2rem;">
                    {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                  </div>
                @endif
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0">{{ $farmer->stall_name }}</h6>
                <div class="text-muted small" style="font-size: 0.76rem;">
                  <span>Contact: <strong>{{ $farmer->contact_person }}</strong></span> &bull;
                  <span>{{ $farmer->user->email ?? '' }}</span>
                </div>
                @if($farmer->bio)
                  <div class="text-muted small mt-1 fst-italic" style="font-size: 0.74rem;">"{{ Str::limit($farmer->bio, 75) }}"</div>
                @endif
              </div>
            </div>

            <div class="pending-farmer-actions d-flex align-items-center gap-2">
              <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="m-0">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-lux-primary py-1 px-3" style="font-size: 0.78rem;">
                  <i class="bi bi-check2-circle"></i> Approve
                </button>
              </form>
              <form action="{{ route('admin.farmers.reject', $farmer) }}" method="POST" class="m-0" onsubmit="return confirm('Reject this application?');">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-lux-danger py-1 px-3" style="font-size: 0.78rem;">
                  Reject
                </button>
              </form>
              <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn-lux-icon" title="View Application Details">
                <i class="bi bi-eye"></i>
              </a>
            </div>
          </div>
        @empty
          <div class="text-center py-4 text-muted">
            <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-1"></i>
            <span class="small">All grower stall applications have been approved and activated!</span>
          </div>
        @endforelse
      </div>
    </div>

    {{-- High-Demand Produce Varieties --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-fire text-danger"></i> High-Demand Harvest Offerings
          </h3>
          <div class="portal-card-subtitle-lux">Ranked by aggregate reservation units across all market hubs</div>
        </div>
        <a href="{{ route('admin.products.index') }}" class="small text-success text-decoration-none fw-semibold">
          Manage Inventory
        </a>
      </div>

      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Rank & Produce Variety</th>
              <th>Grower Stall</th>
              <th>Category</th>
              <th>Price</th>
              <th class="text-end">Units Reserved</th>
            </tr>
          </thead>
          <tbody>
            @foreach($topProducts as $idx => $prod)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <span class="badge rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 26px; height: 26px; background: {{ $idx === 0 ? 'rgba(212, 180, 119, 0.25)' : '#F1F5F2' }}; color: {{ $idx === 0 ? '#8C6B2D' : '#4B5563' }}; font-weight: 800; font-size: 0.76rem;">
                      {{ $idx + 1 }}
                    </span>
                    <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 36px; height: 36px; background: var(--admin-sage-soft);">
                      @if($prod->image)
                        <img src="{{ str_starts_with($prod->image, 'http') ? $prod->image : asset('storage/' . $prod->image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                      @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-success">🌿</div>
                      @endif
                    </div>
                    <div>
                      <div class="fw-bold text-dark">{{ $prod->name }}</div>
                      @if($prod->is_organic)
                        <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 0.68rem; font-weight: 700;">Organic</span>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="text-muted small">{{ $prod->farmer->stall_name ?? '—' }}</td>
                <td>
                  <span class="badge rounded-pill bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem;">
                    {{ $prod->category->name ?? 'Produce' }}
                  </span>
                </td>
                <td class="fw-bold" style="color: var(--admin-emerald);">
                  PKR {{ number_format($prod->price, 2) }}
                  <span class="text-muted fw-normal small">/ {{ $prod->unit }}</span>
                </td>
                <td class="text-end fw-bold text-dark">
                  {{ number_format($prod->orders_count) }} <span class="text-muted fw-normal small">units</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>

  {{-- RIGHT COLUMN: Quick Actions + Live Timeline + Recent Reviews + Contacts --}}
  <div class="col-lg-4 d-flex flex-column gap-4">

    {{-- Quick Command Actions Panel --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <h3 class="portal-card-title-lux">
          <i class="bi bi-lightning-charge-fill text-warning"></i> Command Shortcuts
        </h3>
      </div>
      <div class="portal-card-body-lux d-flex flex-column gap-2 quick-commands-container">
        <a href="{{ route('admin.markets.create') }}" class="btn btn-lux-primary w-100 justify-content-center py-2">
          <i class="bi bi-shop-window"></i> Add New Market Venue
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-lux-outline w-100 justify-content-center py-2">
          <i class="bi bi-tags"></i> Add Produce Category
        </a>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-lux-outline w-100 justify-content-center py-2">
          <i class="bi bi-megaphone"></i> Publish Announcement
        </a>
        <a href="{{ route('admin.farmers.index') }}" class="btn btn-lux-outline w-100 justify-content-center py-2">
          <i class="bi bi-person-badge"></i> Manage Grower Stalls
        </a>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-lux-gold w-100 justify-content-center py-2">
          <i class="bi bi-bar-chart-line"></i> Full Analytics & Export
        </a>
      </div>
    </div>

    {{-- Activity Stream --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-activity text-success"></i> Live Activity Feed
          </h3>
          <div class="portal-card-subtitle-lux">Real-time marketplace transactions & registrations</div>
        </div>
      </div>
      <div class="portal-card-body-lux">
        <div class="activity-timeline-lux">
          @forelse($recentActivity as $act)
            <div class="activity-item-lux">
              <div class="activity-dot-lux" style="border-color: {{ $act['color'] }}; color: {{ $act['color'] }};">
                <i class="bi bi-{{ $act['icon'] }}"></i>
              </div>
              <div class="activity-content-lux">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <a href="{{ $act['url'] }}" class="fw-bold small text-dark text-decoration-none hover-underline">
                    {{ $act['title'] }}
                  </a>
                  <span class="text-muted" style="font-size: 0.68rem;">{{ $act['timestamp']->diffForHumans() }}</span>
                </div>
                <div class="text-muted small" style="font-size: 0.76rem; line-height: 1.4;">
                  {{ $act['desc'] }}
                </div>
              </div>
            </div>
          @empty
            <div class="text-center text-muted small py-3">No recent activities recorded.</div>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Recent Patron Feedback --}}
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <div>
          <h3 class="portal-card-title-lux">
            <i class="bi bi-star-half text-warning"></i> Recent Patron Feedback
          </h3>
          <div class="portal-card-subtitle-lux">Quality feedback & reviews</div>
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="small text-success text-decoration-none fw-semibold">
          Moderate
        </a>
      </div>
      <div class="portal-card-body-lux d-flex flex-column gap-3">
        @forelse($recentReviews as $rev)
          <div class="p-3 rounded-4" style="background: #FAFBF9; border: 1px solid var(--admin-border-subtle);">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="fw-bold small text-dark">{{ $rev->customer->user->name ?? 'Patron' }}</span>
              <div class="text-warning small" style="font-size: 0.75rem;">
                @for($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star{{ $i <= $rev->rating ? '-fill' : '' }}"></i>
                @endfor
              </div>
            </div>
            <div class="text-muted small mb-2" style="font-size: 0.74rem;">
              <span>Stall: <strong>{{ $rev->farmer->stall_name ?? 'Grower' }}</strong></span>
              @if($rev->product)
                &bull; <span>Harvest: {{ $rev->product->name }}</span>
              @endif
            </div>
            @if($rev->comment)
              <p class="small text-secondary mb-0 fst-italic" style="font-size: 0.78rem; line-height: 1.4;">
                "{{ Str::limit($rev->comment, 95) }}"
              </p>
            @endif
          </div>
        @empty
          <div class="text-center text-muted small py-3">No reviews submitted yet.</div>
        @endforelse
      </div>
    </div>

    {{-- Concierge Inquiries --}}
    @if($recentContacts->isNotEmpty())
      <div class="portal-card-lux">
        <div class="portal-card-header-lux">
          <div>
            <h3 class="portal-card-title-lux">
              <i class="bi bi-chat-left-dots text-primary"></i> Concierge Inquiries
            </h3>
            <div class="portal-card-subtitle-lux">Community & grower support tickets</div>
          </div>
          <a href="{{ route('admin.contacts.index') }}" class="small text-success text-decoration-none fw-semibold">
            All Messages
          </a>
        </div>
        <div class="portal-card-body-lux d-flex flex-column gap-2">
          @foreach($recentContacts as $msg)
            <div class="p-3 rounded-4" style="background: #F8FAF9; border: 1px solid var(--admin-border-subtle);">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="fw-bold small text-dark">{{ $msg->name }}</span>
                <span class="badge rounded-pill {{ $msg->status === 'unread' ? 'bg-danger text-white' : 'bg-light text-muted border' }}" style="font-size: 0.65rem;">
                  {{ ucfirst($msg->status) }}
                </span>
              </div>
              <div class="text-dark small fw-semibold text-truncate mb-1" style="font-size: 0.8rem;">{{ $msg->subject }}</div>
              <div class="text-muted small" style="font-size: 0.72rem;">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // 1. Dual-Axis Order Velocity & Revenue Chart
  const ctx = document.getElementById('luxuryAnalyticsChart');
  if (ctx) {
    const labels = @json($chartData['labels']);
    const ordersData = @json($chartData['orders']);
    const revenueData = @json($chartData['revenue']);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Gross Volume (PKR)',
            data: revenueData,
            borderColor: '#D4B477',
            backgroundColor: 'rgba(212, 180, 119, 0.12)',
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointBackgroundColor: '#D4B477',
            pointRadius: 4,
            pointHoverRadius: 6,
            yAxisID: 'y1'
          },
          {
            label: 'Orders Count',
            data: ordersData,
            borderColor: '#16845B',
            backgroundColor: 'transparent',
            tension: 0.35,
            borderWidth: 2,
            borderDash: [4, 4],
            pointBackgroundColor: '#16845B',
            pointRadius: 3,
            pointHoverRadius: 5,
            yAxisID: 'y'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          legend: {
            position: 'top',
            align: 'end',
            labels: {
              boxWidth: 12,
              usePointStyle: true,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11,
                weight: '600'
              },
              color: '#64746B'
            }
          },
          tooltip: {
            backgroundColor: '#071712',
            titleFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '700' },
            bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                if (context.dataset.yAxisID === 'y1') {
                  return ' Volume: PKR ' + Number(context.raw).toLocaleString('en-US', { minimumFractionDigits: 2 });
                }
                return ' Reservations: ' + context.raw;
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
              color: '#8C9B92'
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            grid: { color: 'rgba(0, 0, 0, 0.04)' },
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
              color: '#8C9B92',
              stepSize: 1
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            grid: { drawOnChartArea: false },
            ticks: {
              font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
              color: '#8C6B2D',
              callback: function (val) {
                return 'PKR ' + val;
              }
            }
          }
        }
      }
    });
  }

  // 2. Order Pipeline Status Donut Chart
  const donutCtx = document.getElementById('luxuryDonutChart');
  if (donutCtx) {
    const breakdown = @json($chartData['statusBreakdown']);
    const statusKeys = Object.keys(breakdown);
    const statusVals = Object.values(breakdown);

    new Chart(donutCtx, {
      type: 'doughnut',
      data: {
        labels: statusKeys,
        datasets: [{
          data: statusVals,
          backgroundColor: [
            '#2563EB', // Placed
            '#D97706', // Accepted
            '#7E22CE', // Ready
            '#16845B', // Completed
            '#DC2626'  // Cancelled
          ],
          borderWidth: 2,
          borderColor: '#FFFFFF'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#071712',
            padding: 8,
            cornerRadius: 8,
            callbacks: {
              label: function (ctx) {
                return ' ' + ctx.label + ': ' + ctx.raw + ' orders';
              }
            }
          }
        }
      }
    });
  }
});
</script>
@endpush
