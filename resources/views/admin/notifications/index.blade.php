@extends('layouts.admin')

@section('title', 'Notifications Center — MarketLink Admin')
@section('page-title', 'System Notifications & Alerts')

@section('content')
<div class="d-flex flex-column gap-4">

  {{-- Header Panel --}}
  <div class="portal-card-lux p-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h4 class="fw-bold text-dark mb-1" style="font-family: var(--font-admin-heading);">
          Notifications Center
        </h4>
        <p class="text-muted small mb-0">Stay informed about order completions, new producer applications, and system alerts.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        @if($unreadCount > 0)
          <form action="{{ route('admin.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-lux-outline btn-sm">
              <i class="bi bi-check2-all me-1"></i> Mark All as Read ({{ $unreadCount }})
            </button>
          </form>
        @endif
      </div>
    </div>
  </div>

  {{-- Notification Items List --}}
  <div class="portal-card-lux overflow-hidden">
    @if($notifications->isEmpty())
      <div class="text-center py-5 p-4">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: var(--admin-sage-soft); font-size: 2rem;">
          🔔
        </div>
        <h5 class="fw-bold text-dark mb-1">No Notifications Right Now</h5>
        <p class="text-muted small mb-0">You're all caught up! When new orders or stall applications occur, alerts will appear here.</p>
      </div>
    @else
      <div class="list-group list-group-flush">
        @foreach($notifications as $notif)
          @php
            $isUnread = is_null($notif->read_at);
            $data = $notif->data ?? [];
          @endphp
          <div class="list-group-item p-4 d-flex align-items-start gap-3 {{ $isUnread ? 'bg-light bg-opacity-50' : '' }}" style="border-color: var(--admin-border-subtle);">
            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: {{ $isUnread ? 'rgba(22, 132, 91, 0.12)' : '#F1F5F2' }}; color: {{ $isUnread ? 'var(--admin-emerald)' : 'var(--admin-text-muted)' }}; font-size: 1.2rem;">
              <i class="bi bi-{{ $isUnread ? 'bell-fill' : 'bell' }}"></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <h6 class="fw-bold text-dark mb-0">{{ $data['title'] ?? 'System Notification' }}</h6>
                <span class="text-muted small" style="font-size: 0.74rem;">{{ $notif->created_at->diffForHumans() }}</span>
              </div>
              <p class="text-secondary small mb-2" style="line-height: 1.5;">
                {{ $data['message'] ?? $data['body'] ?? 'You have a new update regarding the MarketLink platform.' }}
              </p>
              @if(isset($data['url']))
                <a href="{{ $data['url'] }}" class="btn btn-sm btn-lux-primary py-1 px-3" style="font-size: 0.78rem;">
                  View Details <i class="bi bi-arrow-right ms-1"></i>
                </a>
              @endif
            </div>
            @if($isUnread)
              <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-muted p-0 text-decoration-none" title="Mark as read">
                  <i class="bi bi-check2 fs-5"></i>
                </button>
              </form>
            @endif
          </div>
        @endforeach
      </div>

      <div class="p-3 border-top">
        {{ $notifications->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
