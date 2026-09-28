@extends('layouts.customer')

@section('title', 'My Profile — MarketLink')
@section('page-title', 'Account & Preferences')

@section('content')
<div class="row g-4">

  {{-- Profile Info Card --}}
  <div class="col-lg-7">
    <div class="portal-card h-100">
      <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
          <h4 class="fw-bold text-dark mb-1" style="font-family: var(--font-heading, 'Plus Jakarta Sans', sans-serif); letter-spacing: -0.01em;">
            Personal Profile
          </h4>
          <p class="text-muted small mb-0">Update your contact identity and default market communication details.</p>
        </div>
        <span class="badge rounded-pill px-3 py-2" style="background: rgba(20, 83, 45, 0.08); color: #15803d; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.05em; text-transform: uppercase;">
          Patron Account
        </span>
      </div>

      <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Avatar Section --}}
        <div class="d-flex flex-column align-items-center text-center mb-5 p-4 rounded-4" style="background: linear-gradient(135deg, rgba(20, 83, 45, 0.03) 0%, rgba(20, 83, 45, 0.08) 100%); border: 1px dashed rgba(20, 83, 45, 0.2);">
          <div class="position-relative mb-3">
            @if($user->profile_image)
              <img src="{{ str_starts_with($user->profile_image, 'http') ? $user->profile_image : asset('storage/' . $user->profile_image) }}" alt="Avatar"
                class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;">
            @else
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                style="width: 100px; height: 100px; font-size: 2.2rem; background: linear-gradient(135deg, #166534, #15803d); border: 3px solid #ffffff;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
            @endif
            <label for="profile_image" class="btn btn-sm btn-dark rounded-circle position-absolute bottom-0 end-0 p-0 d-flex align-items-center justify-content-center shadow" style="width: 32px; height: 32px; cursor: pointer;" title="Upload new photo">
              <i class="bi bi-camera-fill" style="font-size: 0.85rem;"></i>
            </label>
            <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
          </div>
          <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
          <div class="text-muted small">{{ $user->email }}</div>
          <div class="text-muted small mt-1" style="font-size: 0.72rem;">Click the camera icon to upload a personal photo (JPG or PNG, max 2MB)</div>
        </div>

        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">
              Full Legal Name <span class="text-danger">*</span>
            </label>
            <input type="text" name="name" class="form-control rounded-3 py-2 px-3 @error('name') is-invalid @enderror"
              value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">
              Email Address <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" class="form-control rounded-3 py-2 px-3 @error('email') is-invalid @enderror"
              value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="col-12">
            <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">
              Phone Number <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
              <input type="text" name="phone" class="form-control rounded-end-3 border-start-0 py-2 px-3 @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user->phone) }}" placeholder="+60 12 345 6789" required>
            </div>
            <div class="form-text text-muted small" style="font-size: 0.74rem;">Growers will use this number if order collection adjustments arise.</div>
            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <div class="col-12">
            <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.76rem;">
              Primary Locality / Address <span class="text-danger">*</span>
            </label>
            <textarea name="address" rows="3" class="form-control rounded-3 py-2 px-3 @error('address') is-invalid @enderror"
              placeholder="Your residential district or preferred collection area..." required>{{ old('address', $user->address) }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pt-2">
          <button type="submit" class="btn rounded-pill px-5 py-3 fw-bold text-white shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #166534, #15803d); font-size: 0.92rem;">
            <i class="bi bi-check2-circle"></i>
            <span>Save Profile Changes</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  {{-- Right: Security & Account Metadata --}}
  <div class="col-lg-5">
    {{-- Password Change Card --}}
    <div class="portal-card mb-4">
      <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(217, 119, 6, 0.1); color: #d97706; font-size: 1.1rem;">
          <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
          <h5 class="fw-bold text-dark mb-0">Security & Credentials</h5>
          <div class="text-muted small">Update your account login password</div>
        </div>
      </div>

      <form action="{{ route('customer.profile.password') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.74rem;">
            Current Password
          </label>
          <input type="password" name="current_password" class="form-control rounded-3 py-2 px-3 @error('current_password') is-invalid @enderror" required placeholder="••••••••">
          @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.74rem;">
            New Password
          </label>
          <input type="password" name="password" class="form-control rounded-3 py-2 px-3 @error('password') is-invalid @enderror" required placeholder="Minimum 8 characters">
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
          <label class="form-label small fw-bold text-dark text-uppercase" style="letter-spacing: 0.05em; font-size: 0.74rem;">
            Confirm New Password
          </label>
          <input type="password" name="password_confirmation" class="form-control rounded-3 py-2 px-3" required placeholder="Repeat new password">
        </div>

        <button type="submit" class="btn btn-outline-warning text-dark rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem; border-color: #d97706;">
          <i class="bi bi-key-fill text-warning"></i>
          <span>Update Password</span>
        </button>
      </form>
    </div>

    {{-- Account Details Card --}}
    <div class="portal-card">
      <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(20, 83, 45, 0.08); color: #15803d; font-size: 1.1rem;">
          <i class="bi bi-fingerprint"></i>
        </div>
        <div>
          <h5 class="fw-bold text-dark mb-0">Patron Membership</h5>
          <div class="text-muted small">Status & authentication details</div>
        </div>
      </div>

      <div class="d-flex flex-column gap-3 small">
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
          <span class="text-muted">Member Since</span>
          <span class="fw-semibold text-dark">{{ $user->created_at->format('M j, Y') }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
          <span class="text-muted">Account Status</span>
          <span class="badge rounded-pill px-3 py-1" style="background: rgba(20, 83, 45, 0.1); color: #15803d; font-weight: 700;">
            {{ ucfirst($user->status ?? 'Active') }}
          </span>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
          <span class="text-muted">Assigned Role</span>
          <span class="badge rounded-pill px-3 py-1 text-uppercase" style="background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.05em;">
            {{ ucfirst($user->role) }}
          </span>
        </div>
      </div>
    </div>

    {{-- Family Members / Household Sharing --}}
    <div class="portal-card mt-4">
      <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(20, 83, 45, 0.08); color: #15803d; font-size: 1.1rem;">
            <i class="bi bi-people-fill"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-0">Family & Household Sharing</h5>
            <div class="text-muted small">Authorize family members for order pickups</div>
          </div>
        </div>
        <span class="badge rounded-pill bg-light text-dark border px-2 py-1 small">Optional</span>
      </div>

      {{-- List of Added Family Members --}}
      @if(!empty($familyMembers))
        <div class="d-flex flex-column gap-2 mb-3">
          @foreach($familyMembers as $idx => $member)
            <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between bg-light">
              <div>
                <div class="fw-bold text-dark small">{{ $member['name'] }} <span class="badge bg-secondary-subtle text-dark border ms-1" style="font-size: 0.68rem;">{{ $member['relationship'] }}</span></div>
                <div class="text-muted small" style="font-size: 0.75rem;">
                  <i class="bi bi-telephone me-1"></i>{{ $member['phone'] }}
                  @if(!empty($member['authorized_for_pickup']))
                    <span class="text-success ms-2"><i class="bi bi-check-circle-fill"></i> Pickup Authorized</span>
                  @endif
                </div>
              </div>
              <form action="{{ route('customer.profile.family.remove', $idx) }}" method="POST" onsubmit="return confirm('Remove this family member?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-1" style="width: 28px; height: 28px;" title="Remove member">
                  <i class="bi bi-x-lg" style="font-size: 0.75rem;"></i>
                </button>
              </form>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center p-3 text-muted small border rounded-3 bg-light mb-3">
          <i class="bi bi-person-plus fs-4 d-block mb-1 text-secondary"></i>
          No family members added yet. Add family members to authorize them to collect your pre-orders.
        </div>
      @endif

      {{-- Add New Member Form --}}
      <form action="{{ route('customer.profile.family.add') }}" method="POST" class="pt-2">
        @csrf
        <div class="row g-2">
          <div class="col-sm-6">
            <input type="text" name="name" class="form-control form-control-sm rounded-pill" placeholder="Member Name" required>
          </div>
          <div class="col-sm-6">
            <select name="relationship" class="form-select form-select-sm rounded-pill" required>
              <option value="">Relationship...</option>
              <option value="Spouse">Spouse / Partner</option>
              <option value="Parent">Parent</option>
              <option value="Child">Child (Adult)</option>
              <option value="Sibling">Sibling</option>
              <option value="Housemate">Housemate</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-sm-8">
            <input type="text" name="phone" class="form-control form-control-sm rounded-pill" placeholder="Mobile Contact" required>
          </div>
          <div class="col-sm-4">
            <button type="submit" class="btn btn-sm btn-egreen rounded-pill w-100 fw-bold d-flex align-items-center justify-content-center gap-1">
              <i class="bi bi-plus-lg"></i> Add
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
