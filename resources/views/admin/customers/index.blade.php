@extends('layouts.admin')

@section('title', 'Manage Customers')
@section('page-title', 'Community Customers')

@section('content')

{{-- Search Bar --}}
<form method="GET" action="{{ route('admin.customers.index') }}" class="portal-card mb-5" style="padding: 1.25rem 1.5rem;">
  <div class="row g-3 align-items-end">
    <div class="col-md-8">
      <label class="form-label-lux">Search Customers</label>
      <div style="position: relative;">
        <i class="bi bi-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-text-muted); font-size: 0.9rem;"></i>
        <input type="text" name="search" class="form-control form-control-lux" placeholder="Name, email address, or phone..." value="{{ request('search') }}" style="padding-left: 2.5rem;">
      </div>
    </div>
    <div class="col-md-4 d-flex gap-2 align-items-end">
      <button type="submit" class="btn rounded-pill py-2 flex-grow-1" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.86rem; border: none;">
        <i class="bi bi-search me-1"></i> Search
      </button>
      @if(request('search'))
        <a href="{{ route('admin.customers.index') }}" class="btn rounded-pill px-3 py-2" style="background: var(--color-bg); border: 1.5px solid var(--color-border); color: var(--color-text-muted); font-size: 0.84rem;">
          <i class="bi bi-x"></i>
        </a>
      @endif
      <span style="font-size: 0.78rem; white-space: nowrap; font-weight: 700; padding: 0.5rem 0.85rem; border-radius: 100px; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted);">
        {{ $customers->total() }} total
      </span>
    </div>
  </div>
</form>

{{-- Customers Table --}}
<div class="portal-card overflow-hidden">
  @if($customers->isEmpty())
    <div class="text-center py-5" style="color: var(--color-text-muted);">
      <div style="font-size: 3rem; opacity: 0.25; margin-bottom: 0.75rem;">👥</div>
      <p style="font-size: 0.9rem; margin: 0;">No customers found.</p>
    </div>
  @else
    <div class="table-responsive">
      <table class="table-lux table mb-0">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem;">Customer</th>
            <th>Contact Info</th>
            <th class="text-center">Orders</th>
            <th>Status</th>
            <th>Joined</th>
            <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($customers as $customer)
            <tr>
              <td style="padding-left: 1.5rem;">
                <div class="d-flex align-items-center gap-3">
                  <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--color-secondary), var(--color-primary)); display: flex; align-items: center; justify-content: center; font-family: var(--font-serif); font-weight: 700; color: #FFF; font-size: 0.95rem; flex-shrink: 0; border: 1.5px solid rgba(201,168,106,0.35);">
                    {{ strtoupper(substr($customer->user->name ?? 'C', 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-family: var(--font-sans); font-weight: 600; color: var(--color-dark); font-size: 0.9rem;">{{ $customer->user->name ?? 'N/A' }}</div>
                    <div style="font-size: 0.75rem; color: var(--color-text-muted);">#CST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div style="font-size: 0.85rem; color: var(--color-text-dark);">{{ $customer->user->email ?? 'N/A' }}</div>
                <div style="font-size: 0.76rem; color: var(--color-text-muted);">{{ $customer->user->phone ?? '—' }}</div>
              </td>
              <td class="text-center">
                <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">{{ $customer->orders_count }}</span>
              </td>
              <td>
                @if(($customer->user->status ?? 'active') === 'active')
                  <span class="badge-order-status badge-order-ready" style="font-size: 0.72rem;">Active</span>
                @else
                  <span class="badge-order-status badge-order-cancelled" style="font-size: 0.72rem;">Blocked</span>
                @endif
              </td>
              <td style="font-size: 0.82rem; color: var(--color-text-muted);">{{ $customer->created_at ? $customer->created_at->format('M j, Y') : 'N/A' }}</td>
              <td style="padding-right: 1.5rem;">
                <div class="d-flex justify-content-end gap-1">
                  <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.8rem; font-weight: 500;">
                    <i class="bi bi-eye me-1"></i>View
                  </a>
                  <form action="{{ route('admin.customers.toggleStatus', $customer) }}" method="POST" onsubmit="return confirm('Change status for this customer?')">
                    @csrf @method('PATCH')
                    @if(($customer->user->status ?? 'active') === 'active')
                      <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Block Customer">
                        <i class="bi bi-slash-circle"></i>
                      </button>
                    @else
                      <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-success-soft); border: 1px solid rgba(61,139,98,0.35); color: var(--color-success);" title="Unblock Customer">
                        <i class="bi bi-check-circle"></i>
                      </button>
                    @endif
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="p-4">{{ $customers->links() }}</div>
  @endif
</div>

@endsection
