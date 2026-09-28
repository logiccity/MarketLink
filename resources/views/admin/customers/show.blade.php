@extends('layouts.admin')

@section('title', 'Customer: ' . ($customer->user->name ?? 'Details'))
@section('page-title', 'Customer Profile: ' . ($customer->user->name ?? 'Details'))

@section('content')
<div class="p-4">
  <div class="mb-4">
    <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
      <i class="bi bi-arrow-left me-1"></i> Back to Customers
    </a>
  </div>

  <div class="row g-4">
    <!-- Customer Card -->
    <div class="col-lg-4">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <div class="text-center mb-4">
          <div class="rounded-circle bg-success text-white fw-bold d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
            {{ strtoupper(substr($customer->user->name ?? 'C', 0, 1)) }}
          </div>
          <h2 class="h5 fw-bold mb-1">{{ $customer->user->name ?? 'Customer' }}</h2>
          <div class="text-muted small">Registered {{ $customer->created_at ? $customer->created_at->format('M j, Y') : 'N/A' }}</div>
          <div class="mt-2">
            @if(($customer->user->status ?? 'active') === 'active')
              <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Active Account</span>
            @else
              <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1">Blocked / Inactive</span>
            @endif
          </div>
        </div>

        <hr>

        <div class="d-flex flex-column gap-3 small">
          <div>
            <div class="text-muted fw-semibold">Email</div>
            <div class="text-dark">{{ $customer->user->email ?? 'N/A' }}</div>
          </div>
          <div>
            <div class="text-muted fw-semibold">Phone</div>
            <div class="text-dark">{{ $customer->user->phone ?? 'Not provided' }}</div>
          </div>
          <div>
            <div class="text-muted fw-semibold">Address</div>
            <div class="text-dark">{{ $customer->user->address ?? 'Not provided' }}</div>
          </div>
        </div>

        <hr>

        <form action="{{ route('admin.customers.toggleStatus', $customer) }}" method="POST">
          @csrf
          @method('PATCH')
          @if(($customer->user->status ?? 'active') === 'active')
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100 py-2" onclick="return confirm('Block this customer account?')">
              <i class="bi bi-slash-circle me-1"></i> Block Customer Account
            </button>
          @else
            <button type="submit" class="btn btn-success btn-sm rounded-pill w-100 py-2">
              <i class="bi bi-check-circle me-1"></i> Unblock Customer Account
            </button>
          @endif
        </form>
      </div>
    </div>

    <!-- Orders History -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Pre-Order History (Recent {{ $customer->orders->count() }})</h3>

        @if($customer->orders->isEmpty())
          <p class="text-muted small mb-0">This customer has not placed any pre-orders yet.</p>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Order #</th>
                  <th>Farm Stall</th>
                  <th>Market</th>
                  <th>Pickup Date</th>
                  <th>Total</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($customer->orders as $ord)
                  <tr>
                    <td>
                      <a href="{{ route('admin.orders.show', $ord) }}" class="fw-bold text-success text-decoration-none">
                        #{{ $ord->order_number }}
                      </a>
                    </td>
                    <td>{{ $ord->farmer->stall_name ?? 'Stall' }}</td>
                    <td>{{ $ord->market->name ?? 'Market' }}</td>
                    <td>{{ \Carbon\Carbon::parse($ord->pickup_date)->format('M d, Y') }}</td>
                    <td class="fw-bold">PKR {{ number_format($ord->total, 2) }}</td>
                    <td>
                      <span class="badge bg-light text-dark border rounded-pill">{{ $ord->status }}</span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
