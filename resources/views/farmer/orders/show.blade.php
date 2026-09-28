@extends('layouts.farmer')

@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order #' . $order->order_number)

@section('content')
<div class="p-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('farmer.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
      <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>
    <div class="d-flex gap-2">
      <button class="btn btn-light border btn-sm rounded-pill px-3" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Print Packing Slip
      </button>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left: Order Details & Produce Items -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom">
          <div>
            <div class="text-muted small">Pre-Order Reservation</div>
            <h2 class="h5 fw-bold mb-0">#{{ $order->order_number }}</h2>
          </div>
          <div>
            @php
              $badgeClass = match($order->status) {
                'PLACED' => 'bg-warning text-dark',
                'ACCEPTED' => 'bg-info text-dark',
                'READY_FOR_PICKUP' => 'bg-primary text-white',
                'COMPLETED' => 'bg-success text-white',
                'DECLINED', 'CANCELLED' => 'bg-danger text-white',
                default => 'bg-secondary text-white'
              };
            @endphp
            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fs-6">
              {{ str_replace('_', ' ', $order->status) }}
            </span>
          </div>
        </div>

        <h3 class="h6 fw-bold mb-3">Reserved Items</h3>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Produce</th>
                <th>Price</th>
                <th>Qty</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $item->product_name_snapshot }}</div>
                  </td>
                  <td>PKR {{ number_format($item->unit_price_snapshot, 2) }} / {{ $item->unit_snapshot }}</td>
                  <td><span class="badge bg-light text-dark border px-2 py-1">{{ $item->quantity }} {{ $item->unit_snapshot }}</span></td>
                  <td class="text-end fw-bold">PKR {{ number_format($item->subtotal, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-semibold">Order Subtotal:</td>
                <td class="text-end fw-semibold">PKR {{ number_format($order->subtotal, 2) }}</td>
              </tr>
              <tr class="table-light">
                <td colspan="3" class="text-end fw-bold fs-6">Cash Due at Stall:</td>
                <td class="text-end fw-bold fs-6 text-success">PKR {{ number_format($order->total, 2) }}</td>
              </tr>
            </tfoot>
          </table>
        </div>

        @if($order->notes)
          <div class="p-3 rounded-3 bg-light border mt-3">
            <div class="small fw-bold text-dark mb-1"><i class="bi bi-chat-left-text me-1"></i> Customer Packing Note:</div>
            <p class="mb-0 text-muted small">{{ $order->notes }}</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Right: Customer, Pickup & Status Controls -->
    <div class="col-lg-4">
      <!-- Status Actions -->
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Update Order Status</h3>

        @if($order->status === 'PLACED')
          <div class="d-flex flex-column gap-2">
            <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="ACCEPTED">
              <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-semibold">
                <i class="bi bi-check-circle me-1"></i> Accept Pre-Order
              </button>
            </form>

            <button type="button" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-semibold" data-bs-toggle="collapse" data-bs-target="#declineForm">
              <i class="bi bi-x-circle me-1"></i> Decline Pre-Order
            </button>

            <div class="collapse mt-2" id="declineForm">
              <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="DECLINED">
                <div class="mb-2">
                  <label class="form-label small fw-semibold">Reason for declining</label>
                  <textarea name="reason" class="form-control form-control-sm rounded-3" rows="2" placeholder="e.g. Produce unavailable due to weather" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill">Confirm Decline</button>
              </form>
            </div>
          </div>
        @elseif($order->status === 'ACCEPTED')
          <div class="d-flex flex-column gap-2">
            <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="READY_FOR_PICKUP">
              <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                <i class="bi bi-box-seam me-1"></i> Mark Packed & Ready
              </button>
            </form>

            <button type="button" class="btn btn-outline-danger btn-sm w-100 rounded-pill py-2" data-bs-toggle="collapse" data-bs-target="#declineForm">
              Decline / Cancel Order
            </button>

            <div class="collapse mt-2" id="declineForm">
              <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="DECLINED">
                <div class="mb-2">
                  <textarea name="reason" class="form-control form-control-sm rounded-3" rows="2" placeholder="Reason for cancellation" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill">Confirm</button>
              </form>
            </div>
          </div>
        @elseif($order->status === 'READY_FOR_PICKUP')
          <div class="d-flex flex-column gap-2">
            <form action="{{ route('farmer.orders.status', $order) }}" method="POST">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="COMPLETED">
              <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-semibold" onclick="return confirm('Confirm customer picked up produce and paid in cash?')">
                <i class="bi bi-cash-stack me-1"></i> Cash Collected & Completed
              </button>
            </form>
          </div>
        @elseif($order->status === 'COMPLETED')
          <div class="alert alert-success border-0 rounded-3 mb-0 small">
            <i class="bi bi-check-circle-fill me-1"></i> Order completed and settled in cash on {{ $order->completed_at ? $order->completed_at->format('M j, Y') : 'pickup day' }}.
          </div>
        @else
          <div class="alert alert-secondary border-0 rounded-3 mb-0 small">
            Order is {{ $order->status }}.
            @if($order->decline_reason)
              <div class="mt-1 text-danger">Reason: {{ $order->decline_reason }}</div>
            @endif
          </div>
        @endif
      </div>

      <!-- Customer Details -->
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Customer Information</h3>
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="rounded-circle bg-success-subtle text-success p-3">
            <i class="bi bi-person fs-4"></i>
          </div>
          <div>
            <div class="fw-bold">{{ $order->customer->user->name ?? 'Customer' }}</div>
            <div class="text-muted small">{{ $order->customer->user->phone ?? 'No phone' }}</div>
            <div class="text-muted small">{{ $order->customer->user->email ?? '' }}</div>
          </div>
        </div>
      </div>

      <!-- Pickup Details -->
      <div class="bg-white rounded-4 border p-4 shadow-sm">
        <h3 class="h6 fw-bold mb-3">Pickup Location</h3>
        <div class="mb-2">
          <div class="small fw-semibold text-dark">{{ $order->market->name }}</div>
          <div class="text-muted small">{{ $order->market->location }}</div>
        </div>
        <div class="p-3 bg-light rounded-3 border small">
          <div class="fw-semibold text-dark"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($order->pickup_date)->format('l, F j, Y') }}</div>
          @if($order->pickupSlot)
            <div class="text-muted"><i class="bi bi-clock me-1"></i> {{ substr($order->pickupSlot->start_time, 0, 5) }} - {{ substr($order->pickupSlot->end_time, 0, 5) }}</div>
          @endif
          <div class="text-success fw-bold mt-1"><i class="bi bi-cash me-1"></i> Cash on Pickup</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
