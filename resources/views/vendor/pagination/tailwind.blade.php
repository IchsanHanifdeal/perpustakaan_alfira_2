@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile View --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="btn btn-sm btn-disabled">« {!! __('pagination.previous') !!}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-primary">« {!! __('pagination.previous') !!}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-primary">{!! __('pagination.next') !!} »</a>
            @else
                <span class="btn btn-sm btn-disabled">{!! __('pagination.next') !!} »</span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-white opacity-60">
                    {!! __('Showing') !!}
                    <span class="font-semibold text-white">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-semibold text-white">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-semibold text-white">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div class="join">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <button class="join-item btn btn-sm btn-disabled" aria-disabled="true">«</button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="join-item btn btn-sm btn-primary" aria-label="{{ __('pagination.previous') }}">«</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <button class="join-item btn btn-sm btn-disabled">{{ $element }}</button>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button class="join-item btn btn-sm btn-active btn-primary" aria-current="page">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="join-item btn btn-sm btn-neutral hover:btn-primary text-white" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="join-item btn btn-sm btn-primary" aria-label="{{ __('pagination.next') }}">»</a>
                @else
                    <button class="join-item btn btn-sm btn-disabled" aria-disabled="true">»</button>
                @endif
            </div>
        </div>
    </nav>
@endif
