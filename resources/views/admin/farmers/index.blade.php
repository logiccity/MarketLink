@extends('layouts.admin')

@section('title', 'Manage Farmers')
@section('page-title', 'Manage Farmers')

@section('content')

{{-- Filter Tabs + Search --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
  <div class="d-flex gap-2 flex-wrap">
    @foreach(['all' => 'All Farmers', 'pending' => 'Pending', 'approved' => 'Approved', 'suspended' => 'Suspended', 'rejected' => 'Rejected'] as $val => $label)
      <a href="{{ route('admin.farmers.index', ['status' => $val]) }}"
         class="btn btn-sm rounded-pill px-4 py-2"
         style="{{ request('status', 'all') == $val
            ? 'background: var(--color-primary); color: #FFF; font-weight: 700; border: none; font-size: 0.82rem;'
            : 'background: var(--color-surface); border: 1px solid var(--color-border); color: var(--color-text-muted); font-weight: 500; font-size: 0.82rem;' }}">
        {{ $label }}
        @if($val === 'pending' && ($pendingCount ?? 0) > 0)
          <span style="background: var(--color-warning); color: #1A1A1A; border-radius: 100px; padding: 0.1rem 0.45rem; font-size: 0.68rem; font-weight: 800; margin-left: 0.35rem;">{{ $pendingCount }}</span>
        @endif
      </a>
    @endforeach
  </div>
  <form action="{{ route('admin.farmers.index') }}" method="GET" class="d-flex gap-2">
    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search farmers..." value="{{ request('search') }}" style="width: 220px; border-radius: 100px; border: 1.5px solid var(--color-border); font-size: 0.85rem; padding: 0.45rem 1rem;">
    <button type="submit" class="btn btn-sm rounded-pill px-3" style="background: var(--color-bg); border: 1.5px solid var(--color-border); color: var(--color-text-dark); font-weight: 600; font-size: 0.82rem;">
      <i class="bi bi-search me-1"></i>Search
    </button>
  </form>
</div>

{{-- Farmers Table --}}
<div class="portal-card overflow-hidden">
  <div class="table-responsive">
    <table class="table-lux table mb-0" id="farmersTable">
      <thead>
        <tr>
          <th style="padding-left: 1.5rem;">Stall / Farmer</th>
          <th>Email</th>
          <th>Market(s)</th>
          <th class="text-center">Products</th>
          <th>Status</th>
          <th>Joined</th>
          <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($farmers as $farmer)
          <tr>
            <td style="padding-left: 1.5rem;">
              <div class="d-flex align-items-center gap-3">
                <div style="width: 42px; height: 42px; border-radius: 10px; overflow: hidden; flex-shrink: 0; border: 1.5px solid var(--color-border);">
                  @if($farmer->profile_image)
                    <img src="{{ str_starts_with($farmer->profile_image, 'http') ? $farmer->profile_image : asset('storage/' . $farmer->profile_image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                  @else
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; font-family: var(--font-serif); font-weight: 700; color: #FFF; font-size: 1.1rem;">
                      {{ strtoupper(substr($farmer->stall_name, 0, 1)) }}
                    </div>
                  @endif
                </div>
                <div>
                  <div style="font-family: var(--font-sans); font-weight: 600; color: var(--color-dark); font-size: 0.9rem;">{{ $farmer->stall_name }}</div>
                  <div style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $farmer->contact_person ?? $farmer->user->name }}</div>
                </div>
              </div>
            </td>
            <td style="font-size: 0.84rem; color: var(--color-text-muted);">{{ $farmer->user->email ?? '—' }}</td>
            <td style="font-size: 0.84rem; color: var(--color-text-muted);">{{ $farmer->markets->pluck('name')->join(', ') ?: '—' }}</td>
            <td class="text-center">
              <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">{{ $farmer->products_count ?? 0 }}</span>
            </td>
            <td>
              @php
                $statusStyle = [
                  'approved'  => 'background: var(--color-success-soft); color: var(--color-success); border-color: rgba(61,139,98,0.3);',
                  'pending'   => 'background: var(--color-warning-soft); color: #925D07; border-color: rgba(216,155,61,0.3);',
                  'rejected'  => 'background: var(--color-danger-soft); color: var(--color-danger); border-color: rgba(198,91,91,0.3);',
                  'suspended' => 'background: var(--color-bg); color: var(--color-text-muted); border-color: var(--color-border);',
                ];
                $ss = $statusStyle[$farmer->approval_status] ?? $statusStyle['suspended'];
              @endphp
              <span style="font-size: 0.74rem; font-weight: 700; padding: 0.28rem 0.75rem; border-radius: 100px; border: 1px solid; letter-spacing: 0.04em; {{ $ss }}">
                {{ ucfirst($farmer->approval_status) }}
              </span>
            </td>
            <td style="font-size: 0.82rem; color: var(--color-text-muted);">{{ $farmer->created_at->format('d M Y') }}</td>
            <td style="padding-right: 1.5rem;">
              <div class="d-flex gap-1 justify-content-end">
                <a href="{{ route('admin.farmers.show', $farmer) }}" class="btn btn-sm rounded-pill px-2" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark);" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                @if($farmer->isPending())
                  <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-success); color: #FFF; border: none;" title="Approve">
                      <i class="bi bi-check-lg"></i>
                    </button>
                  </form>
                  <form action="{{ route('admin.farmers.reject', $farmer) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Reject" onclick="return confirm('Reject?')">
                      <i class="bi bi-x-lg"></i>
                    </button>
                  </form>
                @elseif($farmer->isApproved())
                  <form action="{{ route('admin.farmers.suspend', $farmer) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-warning-soft); border: 1px solid rgba(216,155,61,0.35); color: #925D07;" title="Suspend" onclick="return confirm('Suspend this farmer?')">
                      <i class="bi bi-slash-circle"></i>
                    </button>
                  </form>
                @elseif($farmer->isSuspended())
                  <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-success); color: #FFF; border: none;" title="Reinstate">
                      <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                  </form>
                @endif
                <form action="{{ route('admin.farmers.destroy', $farmer) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Delete" onclick="return confirm('Permanently delete this farmer? This cannot be undone.')">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5" style="color: var(--color-text-muted); font-size: 0.9rem;">
              <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 0.75rem;"></i>
              No farmers found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-4">{{ $farmers->withQueryString()->links('pagination::bootstrap-5') }}</div>

@endsection
