@if ($paginator->hasPages())
    <nav class="jetfly-pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        {{-- Narrow: prev / next only --}}
        <div class="jetfly-pagination__narrow">
            <ul class="jetfly-pagination__list jetfly-pagination__list--pager">
                @if ($paginator->onFirstPage())
                    <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled">@lang('pagination.previous')</span></li>
                @else
                    <li><a class="jetfly-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
                @endif
                @if ($paginator->hasMorePages())
                    <li><a class="jetfly-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
                @else
                    <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled">@lang('pagination.next')</span></li>
                @endif
            </ul>
        </div>

        {{-- Wide: summary + numbered pages --}}
        <div class="jetfly-pagination__wide">
            <p class="jetfly-pagination__meta">
                @lang('Showing')
                @if ($paginator->firstItem())
                    <strong>{{ $paginator->firstItem() }}</strong> @lang('to') <strong>{{ $paginator->lastItem() }}</strong>
                @else
                    {{ $paginator->count() }}
                @endif
                @lang('of') <strong>{{ $paginator->total() }}</strong> @lang('results')
            </p>
            <ul class="jetfly-pagination__list jetfly-pagination__list--pages">
                @if ($paginator->onFirstPage())
                    <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled" aria-hidden="true">&lsaquo;</span></li>
                @else
                    <li><a class="jetfly-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a></li>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li><span class="jetfly-pagination__ellipsis">{{ $element }}</span></li>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><span class="jetfly-pagination__link jetfly-pagination__link--current" aria-current="page">{{ $page }}</span></li>
                            @else
                                <li><a class="jetfly-pagination__link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li><a class="jetfly-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a></li>
                @else
                    <li><span class="jetfly-pagination__link jetfly-pagination__link--disabled" aria-hidden="true">&rsaquo;</span></li>
                @endif
            </ul>
        </div>
    </nav>
@endif
