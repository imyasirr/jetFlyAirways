@if ($paginator->hasPages())
    <nav class="jetfly-pagination jetfly-pagination--simple" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="jetfly-pagination__list jetfly-pagination__list--pager">
            @if ($paginator->onFirstPage())
                <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled">{!! __('pagination.previous') !!}</span></li>
            @else
                <li><a class="jetfly-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">{!! __('pagination.previous') !!}</a></li>
            @endif
            @if ($paginator->hasMorePages())
                <li><a class="jetfly-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">{!! __('pagination.next') !!}</a></li>
            @else
                <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled">{!! __('pagination.next') !!}</span></li>
            @endif
        </ul>
    </nav>
@endif
