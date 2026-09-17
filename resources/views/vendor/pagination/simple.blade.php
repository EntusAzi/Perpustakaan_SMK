@if ($paginator->hasPages())
    <nav style="display:flex;gap:4px;align-items:center;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:default;">
                &laquo; Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline btn-sm">
                &laquo; Prev
            </a>
        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline btn-sm">
                Next &raquo;
            </a>
        @else
            <span class="btn btn-outline btn-sm" style="opacity:0.4;cursor:default;">
                Next &raquo;
            </span>
        @endif
    </nav>
@endif
