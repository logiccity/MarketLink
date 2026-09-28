@extends('layouts.customer')

@section('title', 'Notifications')
@section('page-title', 'My Notifications')

@section('content')
<div class="p-4">
  <div class="bg-white rounded-4 border shadow-sm p-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h5 class="fw-bold mb-1">Notifications</h5>
        <p class="text-muted small mb-0">Stay up to date with your orders and market activity.</p>
      </div>
      @if($notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('customer.notifications.markAllRead') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-check2-all me-1"></i> Mark All Read
          </button>
        </form>
      @endif
    </div>

    @if($notifications->isEmpty())
      <div class="text-center py-5 text-muted">
        <span style="font-size:3rem;">🔔</span>
        <p class="mt-2 mb-0">You have no notifications yet.</p>
      </div>
    @else
      <div class="list-group list-group-flush">
        @foreach($notifications as $notification)
        @php
          $data = $notification->data;
          $isUnread = is_null($notification->read_at);
        @endphp
        <div class="list-group-item px-0 py-3 d-flex align-items-start gap-3 {{ $isUnread ? 'bg-light rounded-3' : '' }}">
          <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
            {{ $isUnread ? 'bg-success text-white' : 'bg-light text-muted' }}"
            style="width:42px; height:42px; font-size:1.1rem;">
            @if(isset($data['icon']))
              {{ $data['icon'] }}
            @else
              <i class="bi bi-bell"></i>
            @endif
          </div>
          <div class="flex-grow-1">
            <div class="fw-semibold small {{ $isUnread ? 'text-dark' : 'text-muted' }}">
              {{ $data['title'] ?? 'Notification' }}
            </div>
            <div class="text-muted small">{{ $data['message'] ?? '' }}</div>
            <div class="text-muted mt-1" style="font-size:.72rem;">
              {{ $notification->created_at->diffForHumans() }}
            </div>
          </div>
          <div class="flex-shrink-0">
            @if($isUnread)
              <form action="{{ route('customer.notifications.read', $notification->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-2 py-1"
                  style="font-size:.72rem;">Mark Read</button>
              </form>
            @else
              <span class="text-muted" style="font-size:.75rem;">Read</span>
            @endif
          </div>
        </div>
        @endforeach
      </div>

      <div class="mt-4">
        {{ $notifications->links() }}
      </div>
    @endif

  </div>
</div>
@endsection
