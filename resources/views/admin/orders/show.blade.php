@extends('layouts.admin')

@section('title', 'Admin Order #' . $order->order_number)
@section('page-title', 'Order Details: #' . $order->order_number)

@section('content')
<div class="p-4">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
      <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>
    <button class="btn btn-light border btn-sm rounded-pill px-3" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Print Order
    </button>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pb-3 mb-3 border-bottom">
          <div>
            <div class="text-muted small">Pre-Order Reservation</div>
            <h2 class="h5 fw-bold mb-0">#{{ $order->order_number }}</h2>
            <div class="text-muted small">Placed on {{ $order->created_at->format('M j, Y \a\t g:i A') }}</div>
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

        <h3 class="h6 fw-bold mb-3">Order Items</h3>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Produce Item</th>
                <th>Unit Price</th>
                <th>Quantity</th>
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
                  <td>{{ $item->quantity }} {{ $item->unit_snapshot }}</td>
                  <td class="text-end fw-bold">PKR {{ number_format($item->subtotal, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                <td class="text-end fw-semibold">PKR {{ number_format($order->subtotal, 2) }}</td>
              </tr>
              <tr class="table-light">
                <td colspan="3" class="text-end fw-bold fs-6">Total Due in Cash:</td>
                <td class="text-end fw-bold fs-6 text-success">PKR {{ number_format($order->total, 2) }}</td>
              </tr>
            </tfoot>
          </table>
        </div>

        @if($order->notes)
          <div class="p-3 bg-light rounded-3 border mt-3">
            <div class="small fw-bold text-dark mb-1">Customer Packing Note:</div>
            <p class="mb-0 text-muted small">{{ $order->notes }}</p>
          </div>
        @endif
      </div>
    </div>

    <div class="col-lg-4">
      <!-- Parties Info -->
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-3">Customer</h3>
        <div class="mb-3">
          <div class="fw-bold">{{ $order->customer->user->name ?? 'Customer' }}</div>
          <div class="text-muted small">{{ $order->customer->user->email ?? '' }}</div>
          <div class="text-muted small">{{ $order->customer->user->phone ?? '' }}</div>
        </div>

        <hr>

        <h3 class="h6 fw-bold mb-3">Farmer Stall</h3>
        <div class="mb-3">
          <div class="fw-bold">{{ $order->farmer->stall_name ?? 'Farmer' }}</div>
          <div class="text-muted small">Contact: {{ $order->farmer->contact_person }}</div>
          <div class="text-muted small">{{ $order->farmer->user->phone ?? '' }}</div>
        </div>

        <hr>

        <h3 class="h6 fw-bold mb-2">Market Pickup</h3>
        <div class="small text-dark fw-semibold">{{ $order->market->name }}</div>
        <div class="text-muted small mb-2">{{ $order->market->location }}</div>
        <div class="p-2 bg-light rounded-3 border small">
          <div><i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($order->pickup_date)->format('l, M j, Y') }}</div>
          @if($order->pickupSlot)
            <div class="text-muted"><i class="bi bi-clock me-1"></i> {{ substr($order->pickupSlot->start_time, 0, 5) }} - {{ substr($order->pickupSlot->end_time, 0, 5) }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
