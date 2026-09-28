@extends('layouts.admin')

@section('title', 'Contact Messages — MarketLink Admin')
@section('page-title', 'Contact Inbox')

@section('content')

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-5">
  <div>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">Messages submitted through the public contact form.</p>
  </div>
  @php $unreadCount = $messages->where('status', 'unread')->count(); @endphp
  @if($unreadCount > 0)
    <span style="background: var(--color-danger-soft); color: var(--color-danger); border: 1px solid rgba(198,91,91,0.35); border-radius: 100px; padding: 0.35rem 1rem; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
      <i class="bi bi-envelope-exclamation-fill"></i> {{ $unreadCount }} Unread
    </span>
  @endif
</div>

@if($messages->isEmpty())
  <div class="portal-card" style="padding: 4rem 2rem; text-align: center;">
    <div style="font-size: 2.5rem; opacity: 0.25; margin-bottom: 1rem;">📭</div>
    <h5 style="font-family: var(--font-serif); color: var(--color-dark); margin-bottom: 0.5rem;">No Contact Messages Yet</h5>
    <p style="font-size: 0.88rem; color: var(--color-text-muted); margin: 0;">Submissions from the public contact form will appear here.</p>
  </div>
@else
  <div class="portal-card overflow-hidden">
    <div class="table-responsive">
      <table class="table-lux table mb-0" id="contactsTable">
        <thead>
          <tr>
            <th style="padding-left: 1.5rem;">Sender</th>
            <th>Subject & Preview</th>
            <th>Received</th>
            <th>Status</th>
            <th class="text-end" style="padding-right: 1.5rem;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($messages as $msg)
            <tr style="{{ $msg->status === 'unread' ? 'background: rgba(212, 180, 119, 0.04);' : '' }}">
              {{-- Sender --}}
              <td style="padding-left: 1.5rem; padding-top: 1rem; padding-bottom: 1rem;">
                <div class="d-flex align-items-center gap-2">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--color-secondary), var(--color-primary)); display: flex; align-items: center; justify-content: center; font-family: var(--font-serif); font-weight: 700; color: #FFF; font-size: 0.9rem; flex-shrink: 0;">
                    {{ strtoupper(substr($msg->name, 0, 1)) }}
                  </div>
                  <div>
                    <div style="font-weight: 600; font-size: 0.88rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem;">
                      {{ $msg->name }}
                      @if($msg->status === 'unread')
                        <span style="width: 7px; height: 7px; border-radius: 50%; background: var(--color-danger); display: inline-block;"></span>
                      @endif
                    </div>
                    <div style="font-size: 0.76rem; color: var(--color-text-muted);">{{ $msg->email }}</div>
                    @if($msg->phone)
                      <div style="font-size: 0.74rem; color: var(--color-text-muted);">{{ $msg->phone }}</div>
                    @endif
                  </div>
                </div>
              </td>

              {{-- Subject & Preview --}}
              <td style="max-width: 340px;">
                <div style="font-size: 0.88rem; font-weight: 600; color: var(--color-dark); margin-bottom: 0.2rem;">{{ $msg->subject ?? 'General Inquiry' }}</div>
                <div style="font-size: 0.78rem; color: var(--color-text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 300px;">{{ $msg->message }}</div>
              </td>

              {{-- Date --}}
              <td>
                <div style="font-size: 0.86rem; font-weight: 600; color: var(--color-dark);">{{ $msg->created_at->format('M j, Y') }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-muted);">{{ $msg->created_at->format('g:i A') }}</div>
              </td>

              {{-- Status Badge --}}
              <td>
                @if($msg->status === 'unread')
                  <span style="font-size: 0.74rem; font-weight: 700; background: var(--color-danger-soft); color: var(--color-danger); border: 1px solid rgba(198,91,91,0.3); border-radius: 100px; padding: 0.25rem 0.7rem; letter-spacing: 0.04em;">Unread</span>
                @elseif($msg->status === 'replied')
                  <span style="font-size: 0.74rem; font-weight: 700; background: var(--color-success-soft); color: var(--color-success); border: 1px solid rgba(61,139,98,0.3); border-radius: 100px; padding: 0.25rem 0.7rem; letter-spacing: 0.04em;">Replied</span>
                @else
                  <span style="font-size: 0.74rem; font-weight: 700; background: var(--color-bg); color: var(--color-text-muted); border: 1px solid var(--color-border); border-radius: 100px; padding: 0.25rem 0.7rem; letter-spacing: 0.04em;">Read</span>
                @endif
              </td>

              {{-- Actions --}}
              <td style="padding-right: 1.5rem; text-align: right;">
                <div class="d-flex justify-content-end gap-1">
                  <button type="button" class="btn btn-sm rounded-pill px-2"
                    style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark);"
                    data-bs-toggle="modal" data-bs-target="#viewMsg-{{ $msg->id }}" title="View Message">
                    <i class="bi bi-eye"></i>
                  </button>
                  <form action="{{ route('admin.contacts.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm rounded-pill px-2" style="background: var(--color-danger-soft); border: 1px solid rgba(198,91,91,0.35); color: var(--color-danger);" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>

            {{-- View Message Modal --}}
            <div class="modal fade text-start" id="viewMsg-{{ $msg->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lux-lg); overflow: hidden;">
                  {{-- Modal Header --}}
                  <div class="modal-header border-0" style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); padding: 1.4rem 1.6rem;">
                    <div>
                      <h5 class="modal-title" style="font-family: var(--font-serif); color: #FFF; font-weight: 700; margin-bottom: 0.15rem;">{{ $msg->name }}</h5>
                      <div style="font-size: 0.8rem; color: rgba(255,255,255,0.7);">{{ $msg->email }}{{ $msg->phone ? ' · ' . $msg->phone : '' }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  {{-- Modal Body --}}
                  <div class="modal-body" style="padding: 1.75rem; background: var(--color-bg-secondary);">
                    <div style="margin-bottom: 1.25rem;">
                      <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.35rem;">Subject</div>
                      <div style="font-family: var(--font-serif); font-size: 1.05rem; font-weight: 700; color: var(--color-dark);">{{ $msg->subject ?? 'General Inquiry' }}</div>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                      <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.5rem;">Message</div>
                      <div style="background: var(--color-bg); border: 1px solid var(--color-border); border-radius: 12px; padding: 1.2rem 1.4rem; font-size: 0.9rem; color: var(--color-dark); white-space: pre-wrap; line-height: 1.7;">{{ $msg->message }}</div>
                    </div>
                    <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-bottom: 1.25rem;">
                      <i class="bi bi-clock me-1"></i>Received: {{ $msg->created_at->format('D, M j Y · g:i A') }}
                    </div>
                    {{-- Update Status Form --}}
                    <form action="{{ route('admin.contacts.status', $msg) }}" method="POST">
                      @csrf @method('PATCH')
                      <div style="background: var(--color-bg); border: 1px solid var(--color-border); border-radius: 12px; padding: 1.2rem 1.4rem;">
                        <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-text-muted); margin-bottom: 0.85rem;">Update Status</div>
                        <div class="row g-3 align-items-end">
                          <div class="col-sm-4">
                            <label class="form-label-lux">Status</label>
                            <select name="status" class="form-select form-select-lux">
                              <option value="read"    {{ $msg->status === 'read'    ? 'selected' : '' }}>Read</option>
                              <option value="replied" {{ $msg->status === 'replied' ? 'selected' : '' }}>Replied</option>
                              <option value="unread"  {{ $msg->status === 'unread'  ? 'selected' : '' }}>Unread</option>
                            </select>
                          </div>
                          <div class="col-sm-8">
                            <label class="form-label-lux">Internal Reply Notes</label>
                            <input type="text" name="reply_notes" class="form-control form-control-lux" placeholder="Internal notes..." value="{{ $msg->reply_notes }}">
                          </div>
                          <div class="col-12">
                            <button type="submit" class="btn btn-sm rounded-pill px-4" style="background: var(--color-primary); color: #FFF; font-weight: 700; border: none; font-size: 0.85rem; padding: 0.5rem 1.25rem;">
                              <i class="bi bi-check-lg me-1"></i>Save Status
                            </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </tbody>
      </table>
    </div>
    <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--color-border-subtle);">
      {{ $messages->links() }}
    </div>
  </div>
@endif

@endsection

