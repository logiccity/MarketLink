@extends('layouts.farmer')

@section('title', 'Weekly Stock Management')
@section('page-title', 'Weekly Stock Management & Templates')

@section('content')
<div class="p-4">
  <!-- Top Banner / Date Selector -->
  <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
    <div class="row align-items-center g-3">
      <div class="col-md-6">
        <h2 class="h5 fw-bold mb-1">Manage Harvest Inventory for Selected Week</h2>
        <p class="text-muted small mb-0">Set harvest limits per market day so you never oversell your stall capacity.</p>
      </div>
      <div class="col-md-6">
        <form method="GET" action="{{ route('farmer.weekly-stock.index') }}" class="d-flex align-items-center gap-2 justify-content-md-end">
          <label for="date-select" class="form-label mb-0 fw-semibold small text-nowrap">Market Date:</label>
          <input type="date" name="date" id="date-select" class="form-control form-control-sm rounded-3 w-auto" value="{{ $selectedDate }}" onchange="this.form.submit()">
          <noscript><button type="submit" class="btn btn-sm btn-dark">Load</button></noscript>
        </form>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Active Week Stock Editor -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <div>
            <h3 class="h6 fw-bold mb-0">Stock for Market Date: <span class="text-success">{{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</span></h3>
            <span class="text-muted small">Update available quantities and pricing for this specific pickup cycle</span>
          </div>

          <form action="{{ route('farmer.weekly-stock.template.apply') }}" method="POST">
            @csrf
            <input type="hidden" name="week_date" value="{{ $selectedDate }}">
            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" title="Auto-populate quantities from recurring templates below">
              <i class="bi bi-magic me-1"></i>Apply Templates
            </button>
          </form>
        </div>

        @if($weeklyItems->isEmpty())
          <div class="text-center py-5 text-muted border rounded-3 bg-light">
            <span style="font-size: 2.5rem;">🧺</span>
            <p class="mt-2 mb-2">No individual weekly overrides recorded for this date yet.</p>
            <p class="small text-muted mb-3">Apply your recurring template or use your standard catalog inventory.</p>
            <form action="{{ route('farmer.weekly-stock.template.apply') }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="week_date" value="{{ $selectedDate }}">
              <button type="submit" class="btn btn-sm btn-egreen rounded-pill px-4">
                <i class="bi bi-arrow-repeat me-1"></i>Apply Saved Templates to {{ $selectedDate }}
              </button>
            </form>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Product</th>
                  <th style="width: 140px;">Quantity</th>
                  <th style="width: 130px;">Price ($)</th>
                  <th style="width: 160px;">Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($weeklyItems as $item)
                  <tr>
                    <td>
                      <div class="fw-semibold">{{ $item->product->name }}</div>
                      <span class="badge bg-light text-dark border small" style="font-size: 0.7rem;">/ {{ $item->product->unit }}</span>
                    </td>
                    <td>
                      <form id="form-item-{{ $item->id }}" action="{{ route('farmer.weekly-stock.item.update', $item) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantity" class="form-control form-control-sm rounded-3" value="{{ $item->quantity }}" min="0" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" name="price" class="form-control form-control-sm rounded-3" value="{{ $item->price }}" min="0.01" required>
                    </td>
                    <td>
                        <select name="availability_status" class="form-select form-select-sm rounded-3">
                          <option value="available" {{ $item->availability_status === 'available' ? 'selected' : '' }}>Available</option>
                          <option value="low_stock" {{ $item->availability_status === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                          <option value="sold_out" {{ $item->availability_status === 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                          <option value="temporarily_unavailable" {{ $item->availability_status === 'temporarily_unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-dark rounded-pill px-3">
                          Save
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- Recurring Template Setup -->
    <div class="col-lg-4">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <h3 class="h6 fw-bold mb-1">Weekly Recurring Template</h3>
        <p class="text-muted small mb-3">Define standard harvest quantities automatically applied every week.</p>

        <form action="{{ route('farmer.weekly-stock.template.update') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="product_id" class="form-label small fw-semibold">Select Product</label>
            <select name="product_id" id="product_id" class="form-select form-select-sm rounded-3" required>
              <option value="">-- Choose Product --</option>
              @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (${{ number_format($p->price, 2) }}/{{ $p->unit }})</option>
              @endforeach
            </select>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label for="default_quantity" class="form-label small fw-semibold">Default Qty</label>
              <input type="number" name="default_quantity" id="default_quantity" class="form-control form-control-sm rounded-3" value="30" min="0" required>
            </div>
            <div class="col-6">
              <label for="default_price" class="form-label small fw-semibold">Default Price ($)</label>
              <input type="number" step="0.01" name="default_price" id="default_price" class="form-control form-control-sm rounded-3" value="5.00" min="0.01" required>
            </div>
          </div>

          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available" checked>
            <label class="form-check-label small fw-semibold" for="is_available">Available for pre-order</label>
          </div>

          <div class="mb-3">
            <label for="notes" class="form-label small fw-semibold">Notes (Optional)</label>
            <input type="text" name="notes" id="notes" class="form-control form-control-sm rounded-3" placeholder="e.g. Standard morning harvest quota">
          </div>

          <button type="submit" class="btn btn-egreen btn-sm rounded-pill w-100 py-2 fw-semibold">
            <i class="bi bi-save me-1"></i>Save Template
          </button>
        </form>

        @if($templates->isNotEmpty())
          <hr class="my-3">
          <div class="small fw-bold text-muted mb-2">Saved Recurring Templates ({{ $templates->count() }})</div>
          <div class="d-flex flex-column gap-2" style="max-height: 250px; overflow-y: auto;">
            @foreach($templates as $tpl)
              <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light border" style="font-size: 0.8rem;">
                <div>
                  <div class="fw-semibold text-dark">{{ $tpl->product->name }}</div>
                  <div class="text-muted">{{ $tpl->default_quantity }} {{ $tpl->product->unit }} &bull; ${{ number_format($tpl->default_price, 2) }}</div>
                </div>
                <span class="badge {{ $tpl->is_available ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                  {{ $tpl->is_available ? 'Active' : 'Paused' }}
                </span>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
