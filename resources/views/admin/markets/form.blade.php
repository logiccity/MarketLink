<div class="p-4" style="max-width: 850px;">
  <div class="mb-4">
    <a href="{{ route('admin.markets.index') }}" class="btn btn-light btn-sm rounded-pill border">
      <i class="bi bi-arrow-left me-1"></i>Back to Markets
    </a>
  </div>

  <div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4 p-md-5">
      <form action="{{ isset($market) ? route('admin.markets.update', $market) : route('admin.markets.store') }}" 
            method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($market)) @method('PUT') @endif

        <div class="row g-4">
          {{-- Market Name --}}
          <div class="col-12">
            <label for="name" class="form-label fw-semibold text-dark">Market Venue Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $market->name ?? '') }}" required placeholder="e.g. Model Town Farmers Market, F-7 Organic Fair">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Address --}}
          <div class="col-md-8">
            <label for="address" class="form-label fw-semibold text-dark">Full Venue Address <span class="text-danger">*</span></label>
            <input type="text" name="address" id="address" class="form-control rounded-3 @error('address') is-invalid @enderror" value="{{ old('address', $market->address ?? '') }}" required placeholder="e.g. Central Park Grounds, Block J">
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- City --}}
          <div class="col-md-4">
            <label for="city" class="form-label fw-semibold text-dark">City / District</label>
            <input type="text" name="city" id="city" class="form-control rounded-3 @error('city') is-invalid @enderror" value="{{ old('city', $market->city ?? '') }}" placeholder="e.g. Lahore, Islamabad">
            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Operating Days --}}
          <div class="col-12">
            <label class="form-label fw-semibold text-dark">Market Days <span class="text-danger">*</span></label>
            @php
              $currentDays = old('operating_days', $market->operating_days ?? ['Saturday', 'Sunday']);
              if (!is_array($currentDays)) $currentDays = [];
            @endphp
            <div class="d-flex flex-wrap gap-2">
              @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                <label class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 border bg-light cursor-pointer">
                  <input class="form-check-input mt-0" type="checkbox" name="operating_days[]" value="{{ $day }}" id="day_{{ $day }}"
                    {{ in_array($day, $currentDays) ? 'checked' : '' }}>
                  <span class="small fw-semibold text-dark">{{ $day }}</span>
                </label>
              @endforeach
            </div>
            @error('operating_days') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>

          {{-- Hours --}}
          <div class="col-md-6">
            <label for="opening_time" class="form-label fw-semibold text-dark">Opening Time <span class="text-danger">*</span></label>
            <input type="time" name="opening_time" id="opening_time" class="form-control rounded-3 @error('opening_time') is-invalid @enderror" value="{{ old('opening_time', $market->opening_time ?? '08:00') }}" required>
            @error('opening_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="closing_time" class="form-label fw-semibold text-dark">Closing Time <span class="text-danger">*</span></label>
            <input type="time" name="closing_time" id="closing_time" class="form-control rounded-3 @error('closing_time') is-invalid @enderror" value="{{ old('closing_time', $market->closing_time ?? '14:00') }}" required>
            @error('closing_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Map Coordinates --}}
          <div class="col-md-6">
            <label for="latitude" class="form-label fw-semibold text-dark">Latitude (Optional)</label>
            <input type="text" name="latitude" id="latitude" class="form-control rounded-3 @error('latitude') is-invalid @enderror" value="{{ old('latitude', $market->latitude ?? '') }}" placeholder="31.5204">
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label for="longitude" class="form-label fw-semibold text-dark">Longitude (Optional)</label>
            <input type="text" name="longitude" id="longitude" class="form-control rounded-3 @error('longitude') is-invalid @enderror" value="{{ old('longitude', $market->longitude ?? '') }}" placeholder="74.3587">
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Image Upload --}}
          <div class="col-12">
            <label for="image" class="form-label fw-semibold text-dark">Venue Banner / Photo</label>
            @if(isset($market) && $market->image)
              <div class="mb-2 d-flex align-items-center gap-3">
                <img src="{{ str_starts_with($market->image, 'http') ? $market->image : asset('storage/' . $market->image) }}" alt="{{ $market->name }}" class="rounded-3 border" style="height: 70px; width: 120px; object-fit: cover;">
                <div class="text-muted small">Current banner photo. Choose new file below to replace.</div>
              </div>
            @endif
            <input type="file" name="image" id="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
            <div class="form-text small text-muted">JPEG, PNG or WebP. Max 3MB. Landscape photo recommended.</div>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Status --}}
          <div class="col-12">
            <label for="status" class="form-label fw-semibold text-dark">Venue Operational Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-select rounded-3 @error('status') is-invalid @enderror" required>
              <option value="active" {{ old('status', $market->status ?? 'active') === 'active' ? 'selected' : '' }}>🟢 Active (Published to Public Directory)</option>
              <option value="inactive" {{ old('status', $market->status ?? '') === 'inactive' ? 'selected' : '' }}>⚪ Inactive (Closed / Hidden from Marketplace)</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Description --}}
          <div class="col-12">
            <label for="description" class="form-label fw-semibold text-dark">Market Narrative & Shopper Guidelines</label>
            <textarea name="description" id="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror" placeholder="Describe the market atmosphere, parking facilities, local specialties, and collection protocols...">{{ old('description', $market->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Submit Buttons --}}
          <div class="col-12 d-flex gap-3 pt-3 border-top">
            <button type="submit" class="btn btn-egreen rounded-pill px-5 py-2 fw-bold text-white shadow-sm" style="background: var(--color-primary, #15803d);">
              <i class="bi bi-check2-circle me-1"></i>{{ isset($market) ? 'Save Venue Changes' : 'Create Market Venue' }}
            </button>
            <a href="{{ route('admin.markets.index') }}" class="btn btn-light border rounded-pill px-4 py-2">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
