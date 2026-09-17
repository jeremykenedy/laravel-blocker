@if($paginator->hasPages())
    <nav class="lb-actions" aria-label="{{ trans('laravelblocker::laravelblocker.ui.pagination') }}">
        @if($paginator->previousPageUrl())<a class="lb-button" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ trans('laravelblocker::laravelblocker.ui.previous') }}</a>@endif
        <span>{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
        @if($paginator->nextPageUrl())<a class="lb-button" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ trans('laravelblocker::laravelblocker.ui.next') }}</a>@endif
    </nav>
@endif
