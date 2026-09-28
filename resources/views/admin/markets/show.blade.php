@extends('layouts.admin')

@section('title', 'Market Details: ' . $market->name)
@section('page-title', 'Market Details')

@section('content')
<div class="p-4">
  <!-- Back Link & Action Header -->
  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <a href="{{ route('admin.markets.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
      <i class="bi bi-arrow-left me-1"></i>Back to Markets
    </a>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.markets.edit', $market) }}" class="btn btn-egreen rounded-pill px-3">
        <i class="bi bi-pencil me-1"></i>Edit Market
      </a>
      <a href="{{ route('markets.show', $market) }}" target="_blank" class="btn btn-outline-success rounded-pill px-3">
        <i class="bi bi-box-arrow-up-right me-1"></i>Public View
      </a>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <!-- Market Overview Card -->
    <div class="col-lg-5">
      <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden">
        @if($market->image)
          <img src="{{ str_starts_with($market->image, 'http') ? $market->image : asset('storage/' . $market->image) }}" class="w-100" style="height: 220px; object-fit: cover;" alt="{{ $market->name }}">
        @else
          <div class="w-100 d-flex align-items-center justify-content-center text-white" style="height: 180px; background: linear-gradient(135deg, var(--forest-green), var(--emerald));">
            <i class="bi bi-shop-window display-4 opacity-50"></i>
          </div>
        @endif

        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h4 class="fw-bold text-dark mb-0">{{ $market->name }}</h4>
            <span class="badge rounded-pill {{ $market->status === 'active' ? 'bg-success' : 'bg-secondary' }} px-3 py-1">
              {{ ucfirst($market->status) }}
            </span>
          </div>
          <p class="text-muted small mb-3">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $market->address }}{{ $market->city ? ', ' . $market->city : '' }}
          </p>

          @if($market->description)
            <p class="text-secondary small">{{ $market->description }}</p>
          @endif

          <hr class="my-3">

          <div class="row g-3 small">
            <div class="col-6">
              <span class="text-muted d-block">Opening Hours</span>
              <strong class="text-dark">{{ $market->opening_time }} - {{ $market->closing_time }}</strong>
            </div>
            <div class="col-6">
              <span class="text-muted d-block">Coordinates</span>
              <strong class="text-dark">{{ $market->latitude }}, {{ $market->longitude }}</strong>
            </div>
            <div class="col-12">
              <span class="text-muted d-block mb-1">Operating Days</span>
              <div class="d-flex flex-wrap gap-1">
                @if(is_array($market->operating_days))
                  @foreach($market->operating_days as $day)
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">{{ ucfirst($day) }}</span>
                  @endforeach
                @else
                  <span class="text-muted">Not specified</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats & Farmers -->
    <div class="col-lg-7">
      <div class="row g-3 mb-4">
        <div class="col-sm-4">
          <div class="card border-0 rounded-4 shadow-sm p-3 bg-white text-center">
            <div class="text-muted small mb-1">Stalls / Farmers</div>
            <div class="fs-3 fw-bold text-success">{{ $market->farmers->count() }}</div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 rounded-4 shadow-sm p-3 bg-white text-center">
            <div class="text-muted small mb-1">Products Listed</div>
            <div class="fs-3 fw-bold text-dark">{{ $market->products->count() }}</div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 rounded-4 shadow-sm p-3 bg-white text-center">
            <div class="text-muted small mb-1">Total Pre-Orders</div>
            <div class="fs-3 fw-bold text-primary">{{ $market->orders->count() }}</div>
          </div>
        </div>
      </div>

      <!-- Farmers at this Market -->
      <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-white border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
          <h6 class="fw-bold text-dark mb-0">Active Stalls & Farmers ({{ $market->farmers->count() }})</h6>
        </div>
        <div class="card-body px-4 pb-3">
          @forelse($market->farmers as $farmer)
            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.9rem;">
                  {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                </div>
                <div>
                  <div class="fw-semibold text-dark">{{ $farmer->stall_name }}</div>
                  <div class="text-muted small">{{ $farmer->contact_person }} &bull; {{ $farmer->products_count }} products</div>
                </div>
              </div>
              <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                View Stall
              </a>
            </div>
          @empty
            <p class="text-muted small my-3">No farmers are currently assigned to this market.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Orders for this Market -->
  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
      <h6 class="fw-bold text-dark mb-0">Recent Pre-Orders at this Market</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-4">Order #</th>
            <th>Customer</th>
            <th>Farmer</th>
            <th>Pickup Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="text-end pe-4">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($market->orders as $order)
            <tr>
              <td class="ps-4 fw-semibold text-success">#{{ $order->order_number }}</td>
              <td>{{ $order->customer->user->name ?? '—' }}</td>
              <td>{{ $order->farmer->stall_name ?? '—' }}</td>
              <td>{{ $order->pickup_date ? $order->pickup_date->format('d M Y') : '—' }}</td>
              <td class="fw-semibold">PKR {{ number_format($order->total, 2) }}</td>
              <td>
                <span class="badge rounded-pill bg-success-subtle text-success">{{ $order->status }}</span>
              </td>
              <td class="text-end pe-4">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light border rounded-pill">View</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-muted small">No orders recorded for this market yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
