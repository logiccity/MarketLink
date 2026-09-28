@extends('layouts.admin')

@section('title', 'Announcements & Broadcasts — MarketLink Admin')
@section('page-title', 'Announcements & Broadcasts')

@section('content')

{{-- Page Header --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-5">
  <div>
    <div class="d-flex align-items-center gap-2 mb-2">
      <div style="width: 30px; height: 30px; border-radius: 8px; background: var(--color-primary); opacity: 0.9; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; color: #FFF;">
        <i class="bi bi-megaphone-fill"></i>
      </div>
      <span style="font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--color-text-muted);">Communications Network</span>
    </div>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">Publish platform announcements visible to growers, patrons, or across the marketplace directory.</p>
  </div>
  <button type="button" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
    <i class="bi bi-megaphone-fill"></i> Post Announcement
  </button>
</div>

@if($announcements->isEmpty())
  <div class="portal-card" style="padding: 4rem 2rem; text-align: center;">
    <div style="font-size: 2.8rem; opacity: 0.25; margin-bottom: 1rem;">📣</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.5rem;">No Announcements Active</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); max-width: 380px; margin: 0 auto 1.5rem;">Dispatch your first network announcement to alert producers and patrons of seasonal events or scheduled market updates.</p>
    <button type="button" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
      <i class="bi bi-plus-circle"></i> Post Announcement
    </button>
  </div>
@else
  <div class="portal-card overflow-hidden">
    <div class="table-responsive">
      <table class="table-lux table mb-0" id="announcementsTable">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem; min-width: 320px;">Announcement</th>
            <th>Audience</th>
            <th>Published</th>
            <th>Status</th>
            <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($announcements as $ann)
            <tr>
              {{-- Announcement Title & Content --}}
              <td style="padding-left: 1.5rem; padding-top: 1rem; padding-bottom: 1rem;">
                <div style="font-weight: 700; font-size: 0.9rem; color: var(--color-dark); margin-bottom: 0.25rem;">{{ $ann->title }}</div>
                <div style="font-size: 0.78rem; color: var(--color-text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 380px;">{{ $ann->content }}</div>
              </td>

              {{-- Audience Badge --}}
              <td>
                @php
                  $roleBadge = match($ann->target_role) {
                    'farmer'   => 'background: var(--color-warning-soft); color: #925D07; border-color: rgba(216,155,61,0.3);',
                    'customer' => 'background: var(--color-info-soft, rgba(37,99,235,0.08)); color: var(--color-info, #2563EB); border-color: rgba(37,99,235,0.25);',
                    default    => 'background: var(--color-success-soft); color: var(--color-success); border-color: rgba(61,139,98,0.3);'
                  };
                  $roleLabel = match($ann->target_role) {
                    'farmer'   => '🌿 Growers',
                    'customer' => '👥 Patrons',
                    default    => '🌐 All'
                  };
                @endphp
                <span style="font-size: 0.74rem; font-weight: 700; letter-spacing: 0.04em; padding: 0.25rem 0.7rem; border-radius: 100px; border: 1px solid; {{ $roleBadge }}">
                  {{ $roleLabel }}
                </span>
              </td>

              {{-- Published Date --}}
              <td>
                <span style="font-size: 0.84rem; color: var(--color-text-muted);">
                  {{ $ann->published_at ? $ann->published_at->format('M j, Y') : '—' }}
                </span>
                @if(!$ann->published_at)
                  <div style="font-size: 0.72rem; color: var(--color-text-light); font-style: italic;">Draft mode</div>
                @endif
              </td>

              {{-- Status --}}
              <td>
                @if($ann->status === 'published')
                  <span class="badge-order-status badge-order-ready" style="font-size: 0.72rem;">Published</span>
                @elseif($ann->status === 'draft')
                  <span style="font-size: 0.74rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); padding: 0.25rem 0.75rem; border-radius: 100px; letter-spacing: 0.04em;">Draft</span>
                @else
                  <span style="font-size: 0.74rem; font-weight: 700; background: var(--color-bg); border: 1px solid var(--color-border-subtle); color: var(--color-text-light); padding: 0.25rem 0.75rem; border-radius: 100px; letter-spacing: 0.04em;">Archived</span>
                @endif
              </td>

              {{-- Actions --}}
              <td style="padding-right: 1.5rem; text-align: right;">
                <div class="d-flex justify-content-end gap-2">
                  <button type="button" class="btn btn-sm rounded-pill px-3" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary); font-weight: 600; font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#editModal-{{ $ann->id }}">
                    <i class="bi bi-pencil-square me-1"></i>Edit
                  </button>
                  <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST" onsubmit="return confirm('Permanently remove this announcement?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);">
                      <i class="bi bi-trash3"></i>
                    </button>
                  </form>
                </div>

                {{-- Edit Modal --}}
                <div class="modal fade text-start" id="editModal-{{ $ann->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('admin.announcements.update', $ann) }}" method="POST" class="w-100">
                      @csrf @method('PUT')
                      <div class="modal-content border-0" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lux-lg); overflow: hidden;">
                        <div class="modal-header border-0" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); padding: 1.4rem 1.6rem;">
                          <h5 class="modal-title" style="font-family: var(--font-serif); color: #FFF; font-weight: 700;">Edit Announcement</h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" style="padding: 1.6rem; background: var(--color-bg-secondary);">
                          <div class="mb-3">
                            <label class="form-label-lux">Announcement Title <span style="color: var(--color-danger);">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lux" value="{{ $ann->title }}" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label-lux">Content Body <span style="color: var(--color-danger);">*</span></label>
                            <textarea name="content" rows="4" class="form-control form-control-lux" required>{{ $ann->content }}</textarea>
                          </div>
                          <div class="row g-3">
                            <div class="col-6">
                              <label class="form-label-lux">Target Group</label>
                              <select name="target_role" class="form-select form-select-lux">
                                <option value="all"      {{ $ann->target_role === 'all'      ? 'selected' : '' }}>Entire Community</option>
                                <option value="customer" {{ $ann->target_role === 'customer' ? 'selected' : '' }}>Patrons Only</option>
                                <option value="farmer"   {{ $ann->target_role === 'farmer'   ? 'selected' : '' }}>Growers Only</option>
                              </select>
                            </div>
                            <div class="col-6">
                              <label class="form-label-lux">Publication Status</label>
                              <select name="status" class="form-select form-select-lux">
                                <option value="published" {{ $ann->status === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft"     {{ $ann->status === 'draft'     ? 'selected' : '' }}>Draft</option>
                                <option value="archived"  {{ $ann->status === 'archived'  ? 'selected' : '' }}>Archived</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer" style="background: var(--color-bg-secondary); border-top: 1px solid var(--color-border); padding: 1rem 1.6rem;">
                          <button type="button" class="btn btn-sm rounded-pill px-4" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); font-weight: 500;" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none;">Save Changes</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border-subtle);">
      {{ $announcements->links() }}
    </div>
  </div>
@endif

{{-- Create Modal --}}
<div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('admin.announcements.store') }}" method="POST" class="w-100">
      @csrf
      <div class="modal-content border-0" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lux-lg); overflow: hidden;">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); padding: 1.4rem 1.6rem;">
          <h5 class="modal-title" style="font-family: var(--font-serif); color: #FFF; font-weight: 700;">Post New Announcement</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="padding: 1.6rem; background: var(--color-bg-secondary);">
          <div class="mb-3">
            <label class="form-label-lux">Broadcast Title <span style="color: var(--color-danger);">*</span></label>
            <input type="text" name="title" class="form-control form-control-lux" placeholder="e.g. 🍓 Organic Strawberry Harvest Peak Season!" required>
          </div>
          <div class="mb-3">
            <label class="form-label-lux">Announcement Body <span style="color: var(--color-danger);">*</span></label>
            <textarea name="content" rows="4" class="form-control form-control-lux" placeholder="Write full details, guidelines, or notice content..." required></textarea>
          </div>
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label-lux">Target Audience</label>
              <select name="target_role" class="form-select form-select-lux">
                <option value="all">Entire Community</option>
                <option value="customer">Patrons Only</option>
                <option value="farmer">Growers Only</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label-lux">Initial Status</label>
              <select name="status" class="form-select form-select-lux">
                <option value="published">Publish Immediately</option>
                <option value="draft">Save as Draft</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="background: var(--color-bg-secondary); border-top: 1px solid var(--color-border); padding: 1rem 1.6rem;">
          <button type="button" class="btn btn-sm rounded-pill px-4" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); font-weight: 500;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none;"><i class="bi bi-megaphone me-1"></i>Publish Broadcast</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if(request('create'))
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('createAnnouncementModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  });
</script>
@endpush
@endif

@endsection
