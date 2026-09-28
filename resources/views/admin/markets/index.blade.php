@extends('layouts.admin')

@section('title', 'Manage Markets')
@section('page-title', 'Manage Markets')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-5">
  <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">
    <i class="bi bi-shop-window me-1"></i>{{ $markets->total() }} markets registered on the platform
  </p>
  <a href="{{ route('admin.markets.create') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;">
    <i class="bi bi-plus-circle"></i> Add Market
  </a>
</div>

{{-- Markets Grid --}}
<div class="row g-4">
  @forelse($markets as $market)
    <div class="col-md-6 col-xl-4">
      <div class="lux-card h-100" style="display: flex; flex-direction: column;">
        <div style="padding: 1.4rem 1.5rem 1rem; flex-grow: 1;">

          {{-- Market Header --}}
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="d-flex align-items-center gap-3">
              <div style="width: 52px; height: 52px; border-radius: 14px; background: var(--color-sage-soft); border: 1.5px solid rgba(168,201,160,0.4); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">🏪</div>
              <div>
                <div style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 1.05rem; line-height: 1.2;">{{ $market->name }}</div>
                <div style="font-size: 0.8rem; color: var(--color-text-muted);"><i class="bi bi-geo-alt me-1"></i>{{ $market->location }}</div>
              </div>
            </div>
            @if($market->is_active)
              <span class="badge-order-status badge-order-ready" style="font-size: 0.7rem; flex-shrink: 0;">Active</span>
            @else
              <span class="badge-order-status badge-order-cancelled" style="font-size: 0.7rem; flex-shrink: 0;">Inactive</span>
            @endif
          </div>

          {{-- Market Days --}}
          @if(!empty($market->market_days))
            <div class="d-flex flex-wrap gap-1 mb-3">
              @foreach($market->market_days as $day)
                <span style="font-size: 0.7rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.18rem 0.6rem; border-radius: 100px; letter-spacing: 0.04em;">{{ $day }}</span>
              @endforeach
            </div>
          @endif

          {{-- Stats Strip --}}
          <div class="d-flex gap-3 pt-2" style="border-top: 1px solid var(--color-border-subtle);">
            <div style="font-size: 0.8rem; color: var(--color-text-muted);">
              <i class="bi bi-person-badge me-1" style="color: var(--color-secondary);"></i>
              <strong style="color: var(--color-dark);">{{ $market->farmers_count ?? 0 }}</strong> farmers
            </div>
            @if($market->opening_time)
              <div style="font-size: 0.8rem; color: var(--color-text-muted);">
                <i class="bi bi-clock me-1" style="color: var(--color-secondary);"></i>{{ $market->opening_time }} – {{ $market->closing_time }}
              </div>
            @endif
          </div>
        </div>

        {{-- Actions --}}
        <div style="padding: 0.85rem 1.5rem; border-top: 1px solid var(--color-border-subtle); display: flex; gap: 0.5rem; background: var(--color-bg-secondary);">
          <a href="{{ route('admin.markets.edit', $market) }}" class="btn btn-sm rounded-pill flex-grow-1 py-1" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.8rem;">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <a href="{{ route('markets.show', $market) }}" class="btn btn-sm rounded-pill px-3 py-1" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted);" title="Public View" target="_blank">
            <i class="bi bi-eye"></i>
          </a>
          <form action="{{ route('admin.markets.destroy', $market) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm rounded-pill px-3 py-1" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" onclick="return confirm('Delete this market?')">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center" style="padding: 4rem 2rem;">
      <div style="font-size: 3rem; opacity: 0.25; margin-bottom: 1rem;">🏪</div>
      <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.75rem;">No Markets Yet</h5>
      <p style="font-size: 0.88rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">Create the first community market to get started.</p>
      <a href="{{ route('admin.markets.create') }}" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.65rem 1.5rem; font-size: 0.88rem;">
        <i class="bi bi-plus-circle"></i> Add Market
      </a>
    </div>
  @endforelse
</div>

<div class="mt-4">{{ $markets->withQueryString()->links('pagination::bootstrap-5') }}</div>

@endsection
