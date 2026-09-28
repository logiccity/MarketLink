@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" style="margin-top: 0;">
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:0.75rem;padding:0.9rem 1.5rem;background:#f9fafb;border-top:1px solid #e4e9e6;border-radius:0 0 14px 14px;">

        {{-- Left: Record count --}}
        <p style="margin:0;font-size:0.8rem;font-weight:500;color:#6b7280;font-family:'Plus Jakarta Sans','Inter',sans-serif;">
            Showing <strong style="color:#111827;">{{ $paginator->firstItem() }}</strong> &ndash; <strong style="color:#111827;">{{ $paginator->lastItem() }}</strong> of <strong style="color:#111827;">{{ $paginator->total() }}</strong> results
        </p>

        {{-- Right: Page buttons --}}
        <ul style="list-style:none;margin:0;padding:0;display:flex;align-items:center;gap:4px;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li><span style="display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 12px;border-radius:8px;background:#f3f4f6;border:1.5px solid #e5e7eb;color:#9ca3af;font-size:0.78rem;font-weight:600;cursor:not-allowed;user-select:none;font-family:'Plus Jakarta Sans','Inter',sans-serif;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>
                    Prev
                </span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                    style="display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 12px;border-radius:8px;background:#ffffff;border:1.5px solid #d1d5db;color:#374151;font-size:0.78rem;font-weight:600;text-decoration:none;font-family:'Plus Jakarta Sans','Inter',sans-serif;"
                    onmouseover="this.style.background='#ecfdf5';this.style.borderColor='#6ee7b7';this.style.color='#065f46';"
                    onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';this.style.color='#374151';">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>
                    Prev
                </a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;color:#9ca3af;font-size:0.9rem;font-weight:700;letter-spacing:0.08em;">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#123C2F,#15803d);border:1.5px solid transparent;color:#ffffff;font-size:0.8rem;font-weight:700;box-shadow:0 3px 10px rgba(18,60,47,0.35);font-family:'Plus Jakarta Sans','Inter',sans-serif;">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}"
                                style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:#ffffff;border:1.5px solid #d1d5db;color:#374151;font-size:0.8rem;font-weight:600;text-decoration:none;font-family:'Plus Jakarta Sans','Inter',sans-serif;"
                                onmouseover="this.style.background='#ecfdf5';this.style.borderColor='#6ee7b7';this.style.color='#065f46';this.style.boxShadow='0 2px 6px rgba(18,60,47,0.12)';"
                                onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';this.style.color='#374151';this.style.boxShadow='none';">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"
                    style="display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 12px;border-radius:8px;background:#ffffff;border:1.5px solid #d1d5db;color:#374151;font-size:0.78rem;font-weight:600;text-decoration:none;font-family:'Plus Jakarta Sans','Inter',sans-serif;"
                    onmouseover="this.style.background='#ecfdf5';this.style.borderColor='#6ee7b7';this.style.color='#065f46';"
                    onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';this.style.color='#374151';">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>
                </a></li>
            @else
                <li><span style="display:inline-flex;align-items:center;gap:4px;height:34px;padding:0 12px;border-radius:8px;background:#f3f4f6;border:1.5px solid #e5e7eb;color:#9ca3af;font-size:0.78rem;font-weight:600;cursor:not-allowed;user-select:none;font-family:'Plus Jakarta Sans','Inter',sans-serif;">
                    Next
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>
                </span></li>
            @endif

        </ul>
    </div>
</nav>
@endif
