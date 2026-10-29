@php
    $t = fn ($key) => config('lantera.'.app()->getLocale().'.'.$key);
    $chevron = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>';
@endphp

@if ($paginator->hasPages())
    <nav class="pagination" aria-label="{{ $t('pagination_label') }}" data-sync="pagination">
        @if ($paginator->onFirstPage())
            <span class="page-step page-prev is-disabled" aria-hidden="true">{!! $chevron !!}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}#quotes" class="page-step page-prev" rel="prev" aria-label="{{ $t('previous_page') }}" title="{{ $t('previous_page') }}">{!! $chevron !!}</a>
        @endif

        <div class="page-numbers">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-gap" aria-hidden="true">…</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-number" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}#quotes" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        <span class="page-status">{{ str_replace([':current', ':last'], [$paginator->currentPage(), $paginator->lastPage()], $t('page_status')) }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}#quotes" class="page-step page-next" rel="next" aria-label="{{ $t('next_page') }}" title="{{ $t('next_page') }}">{!! $chevron !!}</a>
        @else
            <span class="page-step page-next is-disabled" aria-hidden="true">{!! $chevron !!}</span>
        @endif
    </nav>
@else
    <div data-sync="pagination" hidden></div>
@endif
