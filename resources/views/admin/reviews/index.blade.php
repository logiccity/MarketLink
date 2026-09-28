@extends('layouts.admin')

@section('title', 'Reviews Moderation — MarketLink Admin')
@section('page-title', 'Customer Feedback & Reviews')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-5">
  <div>
    <div class="d-flex align-items-center gap-2 mb-2">
      <div style="width: 30px; height: 30px; border-radius: 8px; background: var(--color-warning-soft); border: 1px solid rgba(216,155,61,0.3); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: #925D07;">★</div>
      <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-text-muted);">Quality & Trust Standards</span>
    </div>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">Audit patron evaluations, authenticate ratings, and moderate public harvest testimonials.</p>
  </div>
  {{-- Filter --}}
  <form method="GET" action="{{ route('admin.reviews.index') }}" class="d-flex align-items-center gap-2">
    <label style="font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap;">Filter</label>
    <select name="status" id="status-filter" class="form-select form-select-sm" style="border-radius: 100px; border: 1.5px solid var(--color-border); font-size: 0.82rem; font-weight: 600; padding: 0.4rem 1rem; min-width: 175px; color: var(--color-dark);" onchange="this.form.submit()">
      <option value="">All States</option>
      <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✓ Approved</option>
      <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
      <option value="hidden"   {{ request('status') === 'hidden'   ? 'selected' : '' }}>🚫 Hidden</option>
    </select>
    @if(request('status'))
      <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm rounded-pill px-3" style="border: 1.5px solid var(--color-border); background: var(--color-bg); color: var(--color-text-muted); font-size: 0.82rem;">
        <i class="bi bi-x"></i>
      </a>
    @endif
  </form>
</div>

@if($reviews->isEmpty())
  <div class="portal-card" style="padding: 4rem 2rem; text-align: center;">
    <div style="font-size: 2.5rem; opacity: 0.25; margin-bottom: 1rem;">⭐</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.5rem;">No Reviews Found</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 360px; margin: 0 auto;">No submissions match the current moderation filter.</p>
  </div>
@else
  <div class="portal-card overflow-hidden">
    <div class="table-responsive">
      <table class="table-lux table mb-0" id="reviewsTable">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem;">Patron</th>
            <th>Grower Stall</th>
            <th>Harvest Item</th>
            <th style="min-width: 280px;">Rating & Testimony</th>
            <th>Status</th>
            <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reviews as $rev)
            <tr>
              <td style="padding-left: 1.5rem; padding-top: 1rem; padding-bottom: 1rem;">
                <div class="d-flex align-items-center gap-2">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; font-family: var(--font-serif); font-weight: 700; color: #FFF; font-size: 0.88rem; flex-shrink: 0;">
                    {{ strtoupper(substr($rev->customer->user->name ?? 'P', 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-weight: 600; font-size: 0.86rem; color: var(--color-dark);">{{ $rev->customer->user->name ?? 'Community Patron' }}</div>
                    <div style="font-size: 0.74rem; color: var(--color-text-muted);">{{ $rev->created_at->format('M j, Y') }}</div>
                  </div>
                </div>
              </td>
              <td>
                <span style="font-size: 0.76rem; font-weight: 700; background: var(--color-success-soft); color: var(--color-success); border: 1px solid rgba(61,139,98,0.25); padding: 0.22rem 0.7rem; border-radius: 100px;">
                  {{ $rev->farmer->stall_name ?? 'Farmer Stall' }}
                </span>
              </td>
              <td>
                <span style="font-size: 0.86rem; font-weight: 600; color: var(--color-dark);">{{ $rev->product->name ?? 'Produce Item' }}</span>
              </td>
              <td style="max-width: 280px;">
                <div class="d-flex align-items-center gap-1 mb-1">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="bi bi-star{{ $s <= $rev->rating ? '-fill' : '' }}" style="font-size: 0.8rem; color: {{ $s <= $rev->rating ? '#D97706' : 'var(--color-border)' }};"></i>
                  @endfor
                  <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.82rem; margin-left: 0.25rem;">{{ $rev->rating }}/5</span>
                </div>
                <p style="font-size: 0.79rem; color: var(--color-text-muted); margin: 0; font-style: italic; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 260px;" title="{{ $rev->comment }}">
                  "{{ $rev->comment }}"
                </p>
              </td>
              <td>
                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST" class="m-0">
                  @csrf @method('PATCH')
                  @php
                    $selectBg = match($rev->status) {
                      'approved' => 'background: var(--color-success-soft); color: var(--color-success); border-color: rgba(61,139,98,0.3);',
                      'hidden'   => 'background: var(--color-danger-soft); color: var(--color-danger); border-color: rgba(198,91,91,0.3);',
                      default    => 'background: var(--color-warning-soft); color: #925D07; border-color: rgba(216,155,61,0.3);'
                    };
                  @endphp
                  <select name="status" class="form-select form-select-sm" style="width: 128px; font-size: 0.76rem; font-weight: 700; border-radius: 100px; border: 1.5px solid; {{ $selectBg }}" onchange="this.form.submit()">
                    <option value="approved" {{ $rev->status === 'approved' ? 'selected' : '' }}>✓ Approved</option>
                    <option value="pending"  {{ $rev->status === 'pending'  ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="hidden"   {{ $rev->status === 'hidden'   ? 'selected' : '' }}>🚫 Hidden</option>
                  </select>
                </form>
              </td>
              <td style="padding-right: 1.5rem; text-align: right;">
                <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" onsubmit="return confirm('Permanently purge this review?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm rounded-pill px-3" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger); font-size: 0.8rem; font-weight: 600;">
                    <i class="bi bi-trash3 me-1"></i>Purge
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border-subtle);">
      {{ $reviews->links() }}
    </div>
  </div>
@endif

@endsection
