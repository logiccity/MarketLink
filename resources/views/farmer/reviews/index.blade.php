@extends('layouts.farmer')

@section('title', 'Customer Reviews & Feedback')
@section('page-title', 'Customer Reviews & Feedback')

@section('content')

{{-- Rating & Breakdown Banner --}}
<div class="row g-4 mb-4">
  {{-- Main Score Card --}}
  <div class="col-lg-4">
    <div class="p-4 rounded-4 border bg-white shadow-sm h-100 d-flex flex-column justify-content-center text-center">
      <div class="text-muted small text-uppercase fw-bold mb-1" style="letter-spacing: 0.06em;">Stall Reputation</div>
      <div class="display-3 fw-bold text-dark mb-1" style="font-family: var(--font-serif, 'Playfair Display', serif);">
        {{ number_format($farmer->average_rating, 1) }}
      </div>
      <div class="d-flex align-items-center justify-content-center gap-1 text-warning mb-2">
        @for($i = 1; $i <= 5; $i++)
          <i class="bi bi-star{{ $i <= round($farmer->average_rating) ? '-fill' : '' }} fs-5"></i>
        @endfor
      </div>
      <div class="text-muted small">
        Based on <strong>{{ $totalReviews }}</strong> verified buyer reviews
      </div>
      @if($pendingRepliesCount > 0)
        <div class="mt-3">
          <a href="{{ route('farmer.reviews.index', ['filter' => 'pending_reply']) }}" class="badge rounded-pill px-3 py-2 text-decoration-none" style="background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3);">
            <i class="bi bi-chat-dots me-1"></i>{{ $pendingRepliesCount }} review(s) awaiting your response
          </a>
        </div>
      @endif
    </div>
  </div>

  {{-- Star Breakdown Card --}}
  <div class="col-lg-8">
    <div class="p-4 rounded-4 border bg-white shadow-sm h-100">
      <h3 class="h6 fw-bold mb-3 text-dark">Rating Distribution</h3>
      <div class="d-flex flex-column gap-2">
        @foreach([5, 4, 3, 2, 1] as $star)
          @php
            $count = $ratingCounts[$star] ?? 0;
            $percent = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
          @endphp
          <div class="d-flex align-items-center gap-3">
            <span class="text-muted small fw-semibold" style="width: 45px;">{{ $star }} ★</span>
            <div class="progress flex-grow-1" style="height: 8px; border-radius: 100px; background: #f1f5f9;">
              <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background: {{ $star >= 4 ? '#15803d' : ($star === 3 ? '#f59e0b' : '#dc2626') }};" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <span class="text-muted small text-end" style="width: 50px;">{{ $count }} ({{ $percent }}%)</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- Filter Pills --}}
<div class="d-flex flex-wrap gap-2 mb-4">
  <a href="{{ route('farmer.reviews.index', ['filter' => 'all']) }}" class="btn btn-sm rounded-pill px-3 py-2 {{ $filter === 'all' ? 'btn-dark' : 'btn-light border text-muted' }}">
    All Reviews ({{ $totalReviews }})
  </a>
  <a href="{{ route('farmer.reviews.index', ['filter' => 'pending_reply']) }}" class="btn btn-sm rounded-pill px-3 py-2 {{ $filter === 'pending_reply' ? 'btn-warning text-dark fw-bold' : 'btn-light border text-muted' }}">
    Needs Response ({{ $pendingRepliesCount }})
  </a>
  @foreach([5, 4, 3, 2, 1] as $star)
    <a href="{{ route('farmer.reviews.index', ['filter' => $star]) }}" class="btn btn-sm rounded-pill px-3 py-2 {{ $filter == $star ? 'btn-dark' : 'btn-light border text-muted' }}">
      {{ $star }} Stars ({{ $ratingCounts[$star] ?? 0 }})
    </a>
  @endforeach
</div>

{{-- Reviews Feed --}}
<div class="bg-white rounded-4 border p-4 shadow-sm">
  <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
    <h3 class="h6 fw-bold mb-0">Patron Feedback Feed</h3>
    <span class="text-muted small">Showing {{ $reviews->total() }} reviews</span>
  </div>

  @if($reviews->isEmpty())
    <div class="text-center py-5 text-muted">
      <span style="font-size: 3rem;">💬</span>
      <p class="mt-2 mb-0 fw-semibold text-dark">No reviews matching this filter.</p>
      <p class="small text-muted">Customer reviews will appear once shoppers collect and rate their orders.</p>
    </div>
  @else
    <div class="d-flex flex-column gap-3">
      @foreach($reviews as $rev)
        <div class="p-4 rounded-4 border bg-white" style="border-color: #e2e8f0 !important;">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 42px; height: 42px; font-size: 1rem; background: linear-gradient(135deg, #166534, #15803d);">
                {{ strtoupper(substr($rev->customer->user->name ?? 'C', 0, 1)) }}
              </div>
              <div>
                <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                  {{ $rev->customer->user->name ?? 'Verified Patron' }}
                </div>
                <div class="text-muted" style="font-size: 0.75rem;">
                  @if($rev->product)
                    Reviewed <strong>{{ $rev->product->name }}</strong> &bull;
                  @endif
                  @if($rev->order)
                    Order #{{ $rev->order->order_number }} &bull;
                  @endif
                  {{ $rev->created_at->diffForHumans() }}
                </div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-1 text-warning bg-light px-3 py-1 rounded-pill border">
              @for($s = 1; $s <= 5; $s++)
                <i class="bi bi-star{{ $s <= $rev->rating ? '-fill' : '' }} small"></i>
              @endfor
              <span class="fw-bold text-dark small ms-1">{{ $rev->rating }}/5</span>
            </div>
          </div>

          @if($rev->comment)
            <div class="p-3 rounded-3 bg-light text-secondary small my-3" style="font-style: italic; line-height: 1.6;">
              "{{ $rev->comment }}"
            </div>
          @endif

          <!-- Farmer Responses -->
          @if($rev->responses->isNotEmpty())
            <div class="mt-3 ps-3 border-start border-3" style="border-color: #15803d !important;">
              @foreach($rev->responses as $resp)
                <div class="p-3 rounded-3 border" style="background: rgba(20, 83, 45, 0.03); border-color: rgba(20, 83, 45, 0.15) !important;">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="fw-bold small d-inline-flex align-items-center gap-1" style="color: #15803d;">
                      <i class="bi bi-reply-fill"></i> Your Stall Reply
                    </span>
                    <span class="text-muted" style="font-size: 0.72rem;">{{ $resp->created_at->diffForHumans() }}</span>
                  </div>
                  <p class="mb-0 text-dark small">{{ $resp->response }}</p>
                </div>
              @endforeach
            </div>
          @else
            <!-- Reply Form Collapse -->
            <div class="mt-2">
              <button class="btn btn-sm rounded-pill px-3 py-1 text-white fw-semibold d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="collapse" data-bs-target="#reply-{{ $rev->id }}" style="background: #15803d; font-size: 0.78rem;">
                <i class="bi bi-reply"></i> Reply to Customer
              </button>
              <div class="collapse mt-3" id="reply-{{ $rev->id }}">
                <form action="{{ route('farmer.reviews.respond', $rev) }}" method="POST" class="p-3 rounded-3 bg-light border">
                  @csrf
                  <label class="form-label small fw-bold text-dark mb-1">Public Stall Response</label>
                  <div class="mb-2">
                    <textarea name="response" rows="3" class="form-control form-control-sm rounded-3" placeholder="Thank the patron for supporting your stall or address any product feedback..." required minlength="3" maxlength="1000"></textarea>
                  </div>
                  <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#reply-{{ $rev->id }}">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-dark rounded-pill px-4">Publish Response</button>
                  </div>
                </form>
              </div>
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <div class="mt-4">
      {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@endsection
