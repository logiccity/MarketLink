@extends('layouts.farmer')

@section('title', 'Pickup Slots')
@section('page-title', 'Market Pickup Window Slots')

@section('content')
<div class="p-4">
  <div class="row g-4">
    <!-- Pickup Slots List -->
    <div class="col-lg-8">
      <div class="bg-white rounded-4 border p-4 shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <div>
            <h2 class="h5 fw-bold mb-1">Scheduled Pickup Windows</h2>
            <p class="text-muted small mb-0">Customer orders are booked into these specific time windows to prevent stall congestion.</p>
          </div>
        </div>

        @if($slots->isEmpty())
          <div class="text-center py-5 text-muted border rounded-3 bg-light">
            <span style="font-size: 2.5rem;">⏰</span>
            <p class="mt-2 mb-1">No pickup slots scheduled yet.</p>
            <p class="small text-muted">Use the form on the right to add your market pickup windows.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Market & Date</th>
                  <th>Time Window</th>
                  <th>Capacity</th>
                  <th>Status</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($slots as $slot)
                  <tr>
                    <td>
                      <div class="fw-semibold">{{ $slot->market->name }}</div>
                      <div class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>{{ $slot->pickup_date->format('D, M j, Y') }}
                      </div>
                    </td>
                    <td>
                      <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                        {{ substr($slot->start_time, 0, 5) }} – {{ substr($slot->end_time, 0, 5) }}
                      </span>
                      @if($slot->cutoff_time)
                        <div class="text-muted" style="font-size: 0.7rem;">
                          Cutoff: {{ $slot->cutoff_time->format('M j, g:i A') }}
                        </div>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height: 6px; width: 70px;">
                          @php $percent = $slot->capacity > 0 ? min(100, round(($slot->booked_count / $slot->capacity) * 100)) : 0; @endphp
                          <div class="progress-bar {{ $percent >= 90 ? 'bg-danger' : ($percent >= 60 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $percent }}%;"></div>
                        </div>
                        <span class="small text-muted">{{ $slot->booked_count }}/{{ $slot->capacity }}</span>
                      </div>
                    </td>
                    <td>
                      @if(!$slot->is_active)
                        <span class="badge bg-secondary rounded-pill">Inactive</span>
                      @elseif($slot->isCutoffPassed())
                        <span class="badge bg-danger-subtle text-danger rounded-pill">Cutoff Passed</span>
                      @elseif($slot->isFull())
                        <span class="badge bg-warning text-dark rounded-pill">Fully Booked</span>
                      @else
                        <span class="badge bg-success-subtle text-success rounded-pill">Open</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <div class="d-flex justify-content-end gap-1">
                        <form action="{{ route('farmer.pickup-slots.toggle', $slot) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" title="{{ $slot->is_active ? 'Deactivate' : 'Activate' }}">
                            <i class="bi bi-toggle-{{ $slot->is_active ? 'on text-success' : 'off' }}"></i>
                          </button>
                        </form>

                        @if($slot->booked_count === 0)
                          <form action="{{ route('farmer.pickup-slots.destroy', $slot) }}" method="POST" onsubmit="return confirm('Delete this pickup slot?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Delete slot">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-4">
            {{ $slots->links() }}
          </div>
        @endif
      </div>
    </div>

    <!-- Create Slot Form -->
    <div class="col-lg-4">
      <div class="bg-white rounded-4 border p-4 shadow-sm">
        <h3 class="h6 fw-bold mb-1">Add Pickup Window</h3>
        <p class="text-muted small mb-3">Schedule slots for upcoming market days.</p>

        @if($markets->isEmpty())
          <div class="alert alert-warning small rounded-3 mb-0">
            Please <a href="{{ route('farmer.markets.index') }}" class="alert-link">join a community market</a> first before configuring pickup slots.
          </div>
        @else
          <form action="{{ route('farmer.pickup-slots.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="market_id" class="form-label small fw-semibold">Market Stall Location <span class="text-danger">*</span></label>
              <select name="market_id" id="market_id" class="form-select form-select-sm rounded-3 @error('market_id') is-invalid @enderror" required>
                @foreach($markets as $m)
                  <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
              </select>
              @error('market_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="pickup_date" class="form-label small fw-semibold">Pickup Date <span class="text-danger">*</span></label>
              <input type="date" name="pickup_date" id="pickup_date" class="form-control form-control-sm rounded-3 @error('pickup_date') is-invalid @enderror" min="{{ date('Y-m-d') }}" value="{{ old('pickup_date', now()->next('Saturday')->format('Y-m-d')) }}" required>
              @error('pickup_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-2 mb-3">
              <div class="col-6">
                <label for="start_time" class="form-label small fw-semibold">Start Time <span class="text-danger">*</span></label>
                <input type="time" name="start_time" id="start_time" class="form-control form-control-sm rounded-3 @error('start_time') is-invalid @enderror" value="{{ old('start_time', '08:30') }}" required>
                @error('start_time')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-6">
                <label for="end_time" class="form-label small fw-semibold">End Time <span class="text-danger">*</span></label>
                <input type="time" name="end_time" id="end_time" class="form-control form-control-sm rounded-3 @error('end_time') is-invalid @enderror" value="{{ old('end_time', '11:00') }}" required>
                @error('end_time')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <label for="capacity" class="form-label small fw-semibold">Slot Capacity (Max Orders) <span class="text-danger">*</span></label>
              <input type="number" name="capacity" id="capacity" class="form-control form-control-sm rounded-3 @error('capacity') is-invalid @enderror" value="{{ old('capacity', 20) }}" min="1" max="100" required>
              <div class="form-text">Limit prevents more pre-orders than you can pack for this window.</div>
              @error('capacity')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="cutoff_hours" class="form-label small fw-semibold">Order Cutoff (Hours Before Start)</label>
              <input type="number" name="cutoff_hours" id="cutoff_hours" class="form-control form-control-sm rounded-3 @error('cutoff_hours') is-invalid @enderror" value="{{ old('cutoff_hours', $farmer->order_cutoff_hours ?? 24) }}" min="1" max="72">
              <div class="form-text">Defaults to your stall profile cutoff setting.</div>
              @error('cutoff_hours')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-egreen btn-sm rounded-pill w-100 py-2 fw-semibold">
              <i class="bi bi-calendar-plus me-1"></i>Publish Pickup Slot
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
