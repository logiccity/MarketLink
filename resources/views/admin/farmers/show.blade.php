@extends('layouts.admin')

@section('title', 'Farmer — {{ $farmer->stall_name }}')
@section('page-title', 'Farmer Profile')

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-4">
  <ol class="breadcrumb" style="background: none; padding: 0; margin: 0; font-size: 0.82rem;">
    <li class="breadcrumb-item">
      <a href="{{ route('admin.farmers.index') }}" style="color: var(--color-secondary); text-decoration: none; font-weight: 600;">
        <i class="bi bi-people me-1"></i>Farmers
      </a>
    </li>
    <li class="breadcrumb-item active" style="color: var(--color-text-muted);">{{ $farmer->stall_name }}</li>
  </ol>
</nav>

<div class="row g-4">

  {{-- ====================================================
       LEFT: Profile Card
       ==================================================== --}}
  <div class="col-lg-4">
    <div class="portal-card text-center" style="padding: 2rem 1.5rem;">

      {{-- Avatar --}}
      <div style="width: 90px; height: 90px; border-radius: 50%; margin: 0 auto 1.25rem; overflow: hidden; border: 3px solid var(--color-border); box-shadow: 0 4px 14px rgba(22,132,91,0.15);">
        @if($farmer->profile_image)
          <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="{{ $farmer->stall_name }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
          <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; font-family: var(--font-serif); font-weight: 800; color: #FFF; font-size: 2rem;">
            {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
          </div>
        @endif
      </div>

      {{-- Name & Contact --}}
      <h4 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); margin-bottom: 0.25rem; font-size: 1.2rem;">{{ $farmer->stall_name }}</h4>
      <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 0.85rem;">{{ $farmer->contact_person }}</p>

      {{-- Status Badge --}}
      @php
        $statusStyle = [
          'approved'  => 'background: var(--color-success-soft); color: var(--color-success); border-color: rgba(61,139,98,0.3);',
          'pending'   => 'background: var(--color-warning-soft); color: #925D07; border-color: rgba(216,155,61,0.3);',
          'rejected'  => 'background: var(--color-danger-soft); color: var(--color-danger); border-color: rgba(198,91,91,0.3);',
          'suspended' => 'background: var(--color-bg); color: var(--color-text-muted); border-color: var(--color-border);',
        ];
        $ss = $statusStyle[$farmer->approval_status] ?? $statusStyle['suspended'];
      @endphp
      <span style="font-size: 0.74rem; font-weight: 700; padding: 0.28rem 0.85rem; border-radius: 100px; border: 1px solid; letter-spacing: 0.05em; text-transform: uppercase; {{ $ss }}">
        {{ ucfirst($farmer->approval_status) }}
      </span>

      {{-- Detail List --}}
      <div style="margin-top: 1.5rem; text-align: left; border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
        @foreach([
          ['label' => 'Email', 'value' => $farmer->user->email ?? '—', 'icon' => 'bi-envelope'],
          ['label' => 'Phone', 'value' => $farmer->user->phone ?? '—', 'icon' => 'bi-telephone'],
          ['label' => 'Joined', 'value' => $farmer->created_at->format('M j, Y'), 'icon' => 'bi-calendar3'],
        ] as $item)
          <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem;">
            <div style="width: 26px; height: 26px; border-radius: 6px; background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
              <i class="bi {{ $item['icon'] }}" style="font-size: 0.72rem; color: var(--color-secondary);"></i>
            </div>
            <div>
              <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); margin-bottom: 0.1rem;">{{ $item['label'] }}</div>
              <div style="font-size: 0.86rem; color: var(--color-dark); font-weight: 500;">{{ $item['value'] }}</div>
            </div>
          </div>
        @endforeach

        {{-- Operating Days --}}
        @if($farmer->operating_days)
          <div style="margin-bottom: 0.75rem;">
            <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); margin-bottom: 0.35rem;">Operating Days</div>
            <div class="d-flex flex-wrap gap-1">
              @foreach(is_array($farmer->operating_days) ? $farmer->operating_days : [$farmer->operating_days] as $day)
                <span style="font-size: 0.7rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.18rem 0.55rem; border-radius: 100px;">{{ $day }}</span>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Markets --}}
        <div style="margin-bottom: 0.75rem;">
          <div style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); margin-bottom: 0.35rem;">Markets</div>
          <div class="d-flex flex-wrap gap-1">
            @forelse($farmer->markets as $m)
              <span style="font-size: 0.72rem; font-weight: 600; background: var(--color-success-soft); color: var(--color-success); border: 1px solid rgba(61,139,98,0.25); padding: 0.2rem 0.6rem; border-radius: 100px;">{{ $m->name }}</span>
            @empty
              <span style="font-size: 0.82rem; color: var(--color-text-muted);">None assigned</span>
            @endforelse
          </div>
        </div>
      </div>

      {{-- Status Update Form --}}
      <form action="{{ route('admin.farmers.status', $farmer) }}" method="POST" style="margin-top: 1.25rem; border-top: 1px solid var(--color-border-subtle); padding-top: 1.25rem;">
        @csrf @method('PATCH')
        <label class="form-label-lux">Update Approval Status</label>
        <div class="d-flex gap-2">
          <select name="status" class="form-select form-select-lux flex-grow-1">
            <option value="approved"  {{ $farmer->approval_status === 'approved'  ? 'selected' : '' }}>Approved</option>
            <option value="pending"   {{ $farmer->approval_status === 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="rejected"  {{ $farmer->approval_status === 'rejected'  ? 'selected' : '' }}>Rejected</option>
            <option value="suspended" {{ $farmer->approval_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
          </select>
          <button type="submit" class="btn rounded-pill px-3" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none; font-size: 0.84rem;">Update</button>
        </div>
      </form>
    </div>

    {{-- Bio Card --}}
    @if($farmer->bio)
      <div class="portal-card mt-3">
        <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.65rem;"><i class="bi bi-person-lines-fill me-1"></i>About / Bio</div>
        <p style="font-size: 0.87rem; color: var(--color-dark); line-height: 1.7; margin: 0;">{{ $farmer->bio }}</p>
      </div>
    @endif
  </div>

  {{-- ====================================================
       RIGHT: Stats + Products + Orders + Reviews
       ==================================================== --}}
  <div class="col-lg-8">

    {{-- Quick Stats --}}
    <div class="row g-3 mb-4">
      @foreach([
        ['label' => 'Products', 'value' => $farmer->products->count(), 'icon' => 'bi-box-seam', 'color' => 'var(--color-secondary)'],
        ['label' => 'Orders',   'value' => $farmer->orders->count(),   'icon' => 'bi-receipt',   'color' => '#2563EB'],
        ['label' => 'Reviews',  'value' => $farmer->reviews->count(),  'icon' => 'bi-star-fill', 'color' => '#D97706'],
      ] as $stat)
        <div class="col-4">
          <div class="portal-card text-center" style="padding: 1.25rem 1rem;">
            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(22,132,91,0.08); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.65rem; color: {{ $stat['color'] }};">
              <i class="bi {{ $stat['icon'] }}" style="font-size: 1.1rem;"></i>
            </div>
            <div style="font-family: var(--font-serif); font-size: 1.75rem; font-weight: 800; color: var(--color-dark); line-height: 1;">{{ $stat['value'] }}</div>
            <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.3rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">{{ $stat['label'] }}</div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Products Table --}}
    <div class="portal-card overflow-hidden mb-3" style="padding: 0;">
      <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; align-items: center; gap: 0.6rem;">
        <i class="bi bi-box-seam" style="color: var(--color-secondary);"></i>
        <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">Products</span>
      </div>
      @if($farmer->products->isEmpty())
        <div style="padding: 2.5rem; text-align: center; color: var(--color-text-muted); font-size: 0.88rem;">No products listed yet.</div>
      @else
        <div class="table-responsive">
          <table class="table-lux table mb-0">
            <thead>
              <tr>
                <th style="padding-left: 1.5rem;">Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($farmer->products->take(8) as $p)
                <tr>
                  <td style="padding-left: 1.5rem; font-weight: 600; color: var(--color-dark); font-size: 0.88rem;">{{ $p->name }}</td>
                  <td style="font-size: 0.82rem; color: var(--color-text-muted);">{{ $p->category->name ?? '—' }}</td>
                  <td style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.9rem;">PKR {{ number_format($p->price ?? $p->price_per_unit ?? 0, 2) }}<span style="font-size: 0.75rem; font-weight: 400; color: var(--color-text-muted); font-family: var(--font-sans);">/{{ $p->unit }}</span></td>
                  <td>
                    @php
                      $pBadge = match($p->status ?? $p->availability_status ?? 'available') {
                        'approved', 'available' => 'background: var(--color-success-soft); color: var(--color-success); border-color: rgba(61,139,98,0.3);',
                        'pending'               => 'background: var(--color-warning-soft); color: #925D07; border-color: rgba(216,155,61,0.3);',
                        default                 => 'background: var(--color-bg); color: var(--color-text-muted); border-color: var(--color-border);',
                      };
                    @endphp
                    <span style="font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 100px; border: 1px solid; {{ $pBadge }}">
                      {{ ucfirst($p->status ?? $p->availability_status ?? 'Active') }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($farmer->products->count() > 8)
          <div style="padding: 0.75rem 1.5rem; border-top: 1px solid var(--color-border-subtle); font-size: 0.8rem; color: var(--color-text-muted);">
            + {{ $farmer->products->count() - 8 }} more products not shown
          </div>
        @endif
      @endif
    </div>

    {{-- Recent Orders --}}
    <div class="portal-card overflow-hidden mb-3" style="padding: 0;">
      <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; align-items: center; gap: 0.6rem;">
        <i class="bi bi-receipt" style="color: #2563EB;"></i>
        <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">Recent Orders</span>
      </div>
      @if($farmer->orders->isEmpty())
        <div style="padding: 2.5rem; text-align: center; color: var(--color-text-muted); font-size: 0.88rem;">No orders found.</div>
      @else
        <div class="table-responsive">
          <table class="table-lux table mb-0">
            <thead>
              <tr>
                <th style="padding-left: 1.5rem;">Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($farmer->orders->take(5) as $o)
                <tr>
                  <td style="padding-left: 1.5rem;">
                    <a href="{{ route('admin.orders.show', $o) }}" style="font-family: var(--font-serif); font-weight: 700; color: var(--color-secondary); text-decoration: none; font-size: 0.9rem;">#{{ $o->order_number }}</a>
                  </td>
                  <td style="font-size: 0.86rem; color: var(--color-text-muted);">{{ $o->customer->user->name ?? '—' }}</td>
                  <td style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 0.9rem;">PKR {{ number_format($o->total ?? $o->total_amount ?? 0, 2) }}</td>
                  <td style="font-size: 0.82rem; color: var(--color-text-muted);">{{ $o->created_at->format('M j') }}</td>
                  <td>
                    @php
                      $oStatusColors = [
                        'PLACED'    => 'badge-order-placed',
                        'CONFIRMED' => 'badge-order-accepted',
                        'READY'     => 'badge-order-ready',
                        'PICKED_UP' => 'badge-order-completed',
                        'CANCELLED' => 'badge-order-cancelled',
                        'NO_SHOW'   => 'badge-order-cancelled',
                      ];
                    @endphp
                    <span class="badge-order-status {{ $oStatusColors[$o->status] ?? 'badge-order-placed' }}" style="font-size: 0.72rem;">{{ str_replace('_', ' ', $o->status) }}</span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Reviews --}}
    <div class="portal-card overflow-hidden" style="padding: 0;">
      <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--color-border-subtle); display: flex; align-items: center; gap: 0.6rem;">
        <i class="bi bi-star-fill" style="color: #D97706;"></i>
        <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">Customer Reviews</span>
      </div>
      @if($farmer->reviews->isEmpty())
        <div style="padding: 2.5rem; text-align: center; color: var(--color-text-muted); font-size: 0.88rem;">No reviews yet.</div>
      @else
        <div style="padding: 0.5rem 0;">
          @foreach($farmer->reviews->take(5) as $r)
            <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--color-border-subtle);">
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem;">
                <span style="font-weight: 600; font-size: 0.87rem; color: var(--color-dark);">{{ $r->customer->user->name ?? 'Customer' }}</span>
                <div style="display: flex; gap: 2px;">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="bi bi-star{{ $s <= $r->rating ? '-fill' : '' }}" style="font-size: 0.75rem; color: {{ $s <= $r->rating ? '#D97706' : 'var(--color-border)' }};"></i>
                  @endfor
                </div>
              </div>
              <p style="font-size: 0.83rem; color: var(--color-text-muted); margin: 0; font-style: italic; line-height: 1.5;">"{{ $r->comment }}"</p>
            </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>
</div>

@endsection
