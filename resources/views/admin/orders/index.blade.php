@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('page-title', 'Pre-Orders Monitoring')

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.orders.index') }}" class="portal-card mb-4" style="padding: 1.25rem 1.5rem;">
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label class="form-label-lux">Search</label>
      <div style="position: relative;">
        <i class="bi bi-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 0.9rem;"></i>
        <input type="text" name="search" class="form-control form-control-lux" placeholder="Order #, customer, stall..." value="{{ request('search') }}" style="padding-left: 2.5rem;">
      </div>
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Status</label>
      <select name="status" class="form-select form-select-lux">
        <option value="">All Statuses</option>
        <option value="PLACED" {{ request('status') === 'PLACED' ? 'selected' : '' }}>Placed</option>
        <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
        <option value="READY" {{ request('status') === 'READY' ? 'selected' : '' }}>Ready for Pickup</option>
        <option value="PICKED_UP" {{ request('status') === 'PICKED_UP' ? 'selected' : '' }}>Picked Up</option>
        <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
        <option value="NO_SHOW" {{ request('status') === 'NO_SHOW' ? 'selected' : '' }}>No Show</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label-lux">Market</label>
      <select name="market_id" class="form-select form-select-lux">
        <option value="">All Markets</option>
        @foreach($markets as $m)
          <option value="{{ $m->id }}" {{ request('market_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2 d-flex gap-2">
      <button type="submit" class="btn rounded-pill py-2 flex-grow-1" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.86rem; border: none;">
        <i class="bi bi-funnel me-1"></i> Filter
      </button>
      @if(request()->hasAny(['search', 'status', 'market_id']))
        <a href="{{ route('admin.orders.index') }}" class="btn rounded-pill px-3" style="background: var(--color-bg); border: 1.5px solid var(--color-border); color: var(--color-text-muted); font-size: 0.84rem;">
          <i class="bi bi-x"></i>
        </a>
      @endif
    </div>
  </div>
</form>

{{-- Orders Table --}}
<div class="portal-card overflow-hidden">
  @if($orders->isEmpty())
    <div class="text-center py-5" style="color: var(--color-text-muted);">
      <div style="font-size: 3rem; opacity: 0.25; margin-bottom: 0.75rem;">📋</div>
      <p style="font-size: 0.9rem; margin: 0;">No orders found matching your filters.</p>
    </div>
  @else
    <div class="table-responsive">
      <table class="table-lux table mb-0">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem;">Order #</th>
            <th>Customer</th>
            <th>Farm Stall</th>
            <th>Market</th>
            <th>Pickup Date</th>
            <th>Total</th>
            <th>Status</th>
            <th class="text-end" style="padding-right: 1.5rem;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $ord)
            @php
              $sc = [
                'PLACED'    => 'badge-order-placed',
                'CONFIRMED' => 'badge-order-accepted',
                'READY'     => 'badge-order-ready',
                'PICKED_UP' => 'badge-order-completed',
                'CANCELLED' => 'badge-order-cancelled',
                'NO_SHOW'   => 'badge-order-cancelled',
              ];
            @endphp
            <tr>
              <td style="padding-left: 1.5rem;">
                <a href="{{ route('admin.orders.show', $ord) }}" style="font-family: var(--font-serif); font-weight: 700; color: var(--color-secondary); text-decoration: none; font-size: 0.9rem;">#{{ $ord->order_number }}</a>
                <div style="font-size: 0.72rem; color: var(--color-text-muted);">{{ $ord->created_at->format('M d, g:i A') }}</div>
              </td>
              <td>
                <div style="font-size: 0.88rem; font-weight: 600; color: var(--color-dark);">{{ $ord->customer->user->name ?? 'Customer' }}</div>
                @if($ord->customer->user->phone ?? false)
                  <div style="font-size: 0.76rem; color: var(--color-text-muted);">{{ $ord->customer->user->phone }}</div>
                @endif
              </td>
              <td style="font-size: 0.86rem; color: var(--color-text-muted);">{{ $ord->farmer->stall_name ?? 'Stall' }}</td>
              <td>
                <span style="font-size: 0.74rem; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.2rem 0.7rem; border-radius: 100px; font-weight: 600;">{{ $ord->market->name ?? 'Market' }}</span>
              </td>
              <td>
                @if($ord->pickup_date)
                  <div style="font-size: 0.86rem; font-weight: 600; color: var(--color-dark);">{{ \Carbon\Carbon::parse($ord->pickup_date)->format('D, M j') }}</div>
                  @if($ord->pickupSlot)
                    <div style="font-size: 0.72rem; color: var(--color-text-muted);">{{ substr($ord->pickupSlot->start_time, 0, 5) }}–{{ substr($ord->pickupSlot->end_time, 0, 5) }}</div>
                  @endif
                @else
                  <span style="color: var(--color-text-light);">—</span>
                @endif
              </td>
              <td>
                <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-success); font-size: 0.95rem;">PKR {{ number_format($ord->total_amount, 2) }}</span>
              </td>
              <td>
                <span class="badge-order-status {{ $sc[$ord->status] ?? 'badge-order-placed' }}" style="font-size: 0.72rem;">
                  {{ str_replace('_', ' ', $ord->status) }}
                </span>
              </td>
              <td style="padding-right: 1.5rem; text-align: right;">
                <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.8rem; font-weight: 500;">
                  <i class="bi bi-eye me-1"></i>View
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="p-4">{{ $orders->links() }}</div>
  @endif
</div>

@endsection
