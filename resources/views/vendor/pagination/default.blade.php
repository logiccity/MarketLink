@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="w-100 mt-4 pt-3 border-top border-light-subtle">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 bg-white p-3 px-4 rounded-4 shadow-sm border" style="border-color: rgba(18, 60, 47, 0.08) !important;">
            
            {{-- Results Counter Text --}}
            <div class="text-secondary small fw-medium">
                Showing <span class="fw-bold text-dark">{{ $paginator->firstItem() }}</span> to <span class="fw-bold text-dark">{{ $paginator->lastItem() }}</span> of <span class="fw-bold text-dark">{{ $paginator->total() }}</span> results
            </div>

            {{-- Pagination Buttons --}}
            <ul class="pagination pagination-emerald mb-0 gap-1 align-items-center">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span class="page-link rounded-3 border-0 bg-light text-muted opacity-50 px-3 py-2 fs-7" aria-hidden="true">
                            <i class="bi bi-chevron-left me-1"></i> Prev
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link rounded-3 border-0 bg-light text-dark fw-semibold px-3 py-2 fs-7 hover-emerald" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                            <i class="bi bi-chevron-left me-1"></i> Prev
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0 text-muted px-2 fs-7">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link rounded-3 border-0 fw-bold px-3 py-2 fs-7 shadow-sm text-white" style="background: var(--color-primary, #123C2F);">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link rounded-3 border-0 bg-light text-dark fw-medium px-3 py-2 fs-7 hover-emerald" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link rounded-3 border-0 bg-light text-dark fw-semibold px-3 py-2 fs-7 hover-emerald" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                            Next <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span class="page-link rounded-3 border-0 bg-light text-muted opacity-50 px-3 py-2 fs-7" aria-hidden="true">
                            Next <i class="bi bi-chevron-right ms-1"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
