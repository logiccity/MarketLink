@extends('layouts.admin')

@section('title', 'Global Search — MarketLink Admin')
@section('page-title', 'Global System Search')

@section('content')
<div class="d-flex flex-column gap-4">

  {{-- Search Query Bar --}}
  <div class="portal-card-lux p-4">
    <form action="{{ route('admin.search') }}" method="GET" class="d-flex gap-2 align-items-center">
      <div class="position-relative flex-grow-1">
        <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted fs-5 ms-3"></i>
        <input type="text" name="q" value="{{ $q }}" class="form-control rounded-pill py-2 ps-5 pe-4" style="border: 1.5px solid var(--admin-border); font-size: 0.95rem;" placeholder="Search by order number, customer name, email, farmer, stall, market, or product..." autofocus>
      </div>
      <button type="submit" class="btn btn-lux-primary py-2 px-4">
        Search
      </button>
      @if($q)
        <a href="{{ route('admin.search') }}" class="btn btn-lux-outline py-2 px-3">
          Clear
        </a>
      @endif
    </form>

    @if($q)
      <div class="mt-3 text-muted small d-flex align-items-center gap-2">
        <span>Found <strong>{{ $total }}</strong> records matching "<strong>{{ $q }}</strong>"</span>
      </div>
    @endif
  </div>

  @if($q && $total === 0)
    <div class="portal-card-lux text-center py-5 p-4">
      <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: var(--admin-sage-soft); font-size: 2rem;">
        🔍
      </div>
      <h4 class="fw-bold text-dark mb-1">No Matches Found</h4>
      <p class="text-muted small mb-0">We couldn't locate any records matching "{{ $q }}". Try searching by a different name, order ID, or keyword.</p>
    </div>
  @endif

  {{-- Orders Matches --}}
  @if($orders->isNotEmpty())
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <h3 class="portal-card-title-lux">
          <i class="bi bi-receipt text-success"></i> Orders ({{ $orders->count() }})
        </h3>
        <a href="{{ route('admin.orders.index') }}" class="small text-decoration-none fw-semibold text-success">View All Orders</a>
      </div>
      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Grower / Stall</th>
              <th>Market</th>
              <th>Total</th>
              <th>Status</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $o)
              <tr>
                <td class="fw-bold">#{{ $o->order_number }}</td>
                <td>{{ $o->customer->user->name ?? '—' }}</td>
                <td>{{ $o->farmer->stall_name ?? '—' }}</td>
                <td>{{ $o->market->name ?? '—' }}</td>
                <td class="fw-bold" style="color: var(--admin-emerald);">PKR {{ number_format($o->total, 2) }}</td>
                <td>
                  <span class="badge-lux badge-lux-{{ strtolower($o->status === 'COMPLETED' ? 'completed' : ($o->status === 'PLACED' ? 'placed' : ($o->status === 'READY_FOR_PICKUP' ? 'ready' : 'accepted'))) }}">
                    {{ $o->status }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.orders.show', $o) }}" class="btn-lux-icon" title="View Order">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- Farmers Matches --}}
  @if($farmers->isNotEmpty())
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <h3 class="portal-card-title-lux">
          <i class="bi bi-person-badge text-warning"></i> Farmers & Stalls ({{ $farmers->count() }})
        </h3>
        <a href="{{ route('admin.farmers.index') }}" class="small text-decoration-none fw-semibold text-success">All Farmers</a>
      </div>
      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Stall / Farm</th>
              <th>Contact Person</th>
              <th>Email</th>
              <th>Status</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($farmers as $f)
              <tr>
                <td class="fw-bold">{{ $f->stall_name }}</td>
                <td>{{ $f->contact_person }}</td>
                <td class="text-muted">{{ $f->user->email ?? '—' }}</td>
                <td>
                  <span class="badge-lux {{ $f->approval_status === 'approved' ? 'badge-lux-completed' : 'badge-lux-accepted' }}">
                    {{ ucfirst($f->approval_status) }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('admin.farmers.show', $f) }}" class="btn-lux-icon" title="View Profile">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- Customers Matches --}}
  @if($customers->isNotEmpty())
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <h3 class="portal-card-title-lux">
          <i class="bi bi-people text-primary"></i> Customers ({{ $customers->count() }})
        </h3>
        <a href="{{ route('admin.customers.index') }}" class="small text-decoration-none fw-semibold text-success">All Customers</a>
      </div>
      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Patron Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Joined Date</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($customers as $c)
              <tr>
                <td class="fw-bold">{{ $c->user->name ?? '—' }}</td>
                <td>{{ $c->user->email ?? '—' }}</td>
                <td class="text-muted">{{ $c->user->phone ?? '—' }}</td>
                <td>{{ $c->created_at->format('M j, Y') }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.customers.show', $c) }}" class="btn-lux-icon" title="View Customer">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- Products Matches --}}
  @if($products->isNotEmpty())
    <div class="portal-card-lux">
      <div class="portal-card-header-lux">
        <h3 class="portal-card-title-lux">
          <i class="bi bi-box-seam text-success"></i> Harvest Products ({{ $products->count() }})
        </h3>
        <a href="{{ route('admin.products.index') }}" class="small text-decoration-none fw-semibold text-success">All Products</a>
      </div>
      <div class="table-responsive">
        <table class="table-lux-master">
          <thead>
            <tr>
              <th>Product</th>
              <th>Producer</th>
              <th>Category</th>
              <th>Price</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $p)
              <tr>
                <td class="fw-bold">{{ $p->name }}</td>
                <td>{{ $p->farmer->stall_name ?? '—' }}</td>
                <td>{{ $p->category->name ?? 'Standard' }}</td>
                <td class="fw-bold" style="color: var(--admin-emerald);">PKR {{ number_format($p->price, 2) }}/{{ $p->unit }}</td>
                <td>
                  <span class="badge-lux {{ $p->availability_status === 'available' ? 'badge-lux-completed' : 'badge-lux-cancelled' }}">
                    {{ ucfirst($p->availability_status) }}
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

</div>
@endsection
