@extends('layouts.admin')

@section('title', 'Produce Categories')
@section('page-title', 'Produce Categories')

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-5">
  <div>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">Organize seasonal farm produce into easy-to-browse categories for customers.</p>
  </div>
  <button type="button" class="btn-lux-primary btn d-inline-flex align-items-center gap-2" style="border-radius: 100px; padding: 0.6rem 1.4rem; font-size: 0.88rem;" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
    <i class="bi bi-plus-circle"></i> Add Category
  </button>
</div>

{{-- Table --}}
<div class="portal-card overflow-hidden">
  <div class="table-responsive">
    <table class="table-lux table mb-0">
      <thead>
        <tr>
          <th style="width: 60px; padding-left: 1.5rem;">Icon</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Description</th>
          <th class="text-center">Products</th>
          <th>Status</th>
          <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td style="padding-left: 1.5rem; text-align: center;"><x-category-icon :category="$cat" size="0.85rem" /></td>
            <td>
              <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">{{ $cat->name }}</span>
            </td>
            <td>
              <code style="font-size: 0.78rem; background: var(--color-bg); padding: 0.2rem 0.5rem; border-radius: 4px; color: var(--color-text-muted);">{{ $cat->slug }}</code>
            </td>
            <td style="font-size: 0.83rem; color: var(--color-text-muted);">{{ Str::limit($cat->description, 60) ?? '—' }}</td>
            <td class="text-center">
              <span style="font-family: var(--font-serif); font-weight: 700; color: var(--color-dark); font-size: 0.95rem;">{{ $cat->products_count }}</span>
            </td>
            <td>
              @if($cat->status === 'active')
                <span class="badge-order-status badge-order-ready" style="font-size: 0.72rem;">Active</span>
              @else
                <span class="badge-order-status badge-order-cancelled" style="font-size: 0.72rem;">Inactive</span>
              @endif
            </td>
            <td style="padding-right: 1.5rem;">
              <div class="d-flex justify-content-end gap-1">
                <button type="button" class="btn btn-sm rounded-pill px-2" style="background: var(--color-sage-soft); border: 1px solid rgba(168,201,160,0.4); color: var(--color-secondary);" data-bs-toggle="modal" data-bs-target="#editCategoryModal-{{ $cat->id }}" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                @if($cat->products_count === 0)
                  <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                @endif
              </div>

              {{-- Edit Modal --}}
              <div class="modal fade text-start" id="editCategoryModal-{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <form action="{{ route('admin.categories.update', $cat) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content border-0" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lux-lg); overflow: hidden;">
                      <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); border: none; padding: 1.4rem 1.6rem;">
                        <h5 class="modal-title" style="font-family: var(--font-serif); color: #FFF; font-weight: 700;">Edit: {{ $cat->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body" style="padding: 1.6rem; background: var(--color-bg-secondary);">
                        <div class="mb-3">
                          <label class="form-label-lux">Category Name <span style="color: var(--color-danger);">*</span></label>
                          <input type="text" name="name" class="form-control form-control-lux" value="{{ $cat->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label-lux">Emoji / Icon</label>
                          <input type="text" name="icon" class="form-control form-control-lux" value="{{ $cat->icon }}" placeholder="e.g. 🥦 or 🍎">
                        </div>
                        <div class="mb-3">
                          <label class="form-label-lux">Description</label>
                          <textarea name="description" rows="2" class="form-control form-control-lux">{{ $cat->description }}</textarea>
                        </div>
                        <div class="mb-0">
                          <label class="form-label-lux">Status</label>
                          <select name="status" class="form-select form-select-lux">
                            <option value="active" {{ $cat->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $cat->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer" style="background: var(--color-bg-secondary); border-top: 1px solid var(--color-border); padding: 1rem 1.6rem;">
                        <button type="button" class="btn btn-sm rounded-pill px-4" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); font-weight: 500;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none;">Update Category</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5" style="color: var(--color-text-muted);">
              <i class="bi bi-tags" style="font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 0.75rem;"></i>
              No categories created yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Create Category Modal --}}
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('admin.categories.store') }}" method="POST">
      @csrf
      <div class="modal-content border-0" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lux-lg); overflow: hidden;">
        <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); border: none; padding: 1.4rem 1.6rem;">
          <h5 class="modal-title" style="font-family: var(--font-serif); color: #FFF; font-weight: 700;">Add New Produce Category</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="padding: 1.6rem; background: var(--color-bg-secondary);">
          <div class="mb-3">
            <label class="form-label-lux">Category Name <span style="color: var(--color-danger);">*</span></label>
            <input type="text" name="name" class="form-control form-control-lux" placeholder="e.g. Berries & Stone Fruits" required>
          </div>
          <div class="mb-3">
            <label class="form-label-lux">Emoji / Icon</label>
            <input type="text" name="icon" class="form-control form-control-lux" placeholder="e.g. 🍓 or 🥑">
          </div>
          <div class="mb-3">
            <label class="form-label-lux">Description</label>
            <textarea name="description" rows="2" class="form-control form-control-lux" placeholder="Short description of items in this category..."></textarea>
          </div>
          <div class="mb-0">
            <label class="form-label-lux">Status</label>
            <select name="status" class="form-select form-select-lux">
              <option value="active" selected>Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer" style="background: var(--color-bg-secondary); border-top: 1px solid var(--color-border); padding: 1rem 1.6rem;">
          <button type="button" class="btn btn-sm rounded-pill px-4" style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-muted); font-weight: 500;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none;">Save Category</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if(request('create'))
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('createCategoryModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
      var modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  });
</script>
@endpush
@endif

@endsection
