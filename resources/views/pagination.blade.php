@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Paginación') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-slate-200 cursor-not-allowed leading-5 rounded-lg">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-slate-200 leading-5 rounded-lg hover:text-[#E72085] focus:outline-none focus:ring ring-[#E72085]/20 focus:border-[#E72085] active:bg-[#E72085]/5 active:text-[#E72085] transition ease-in-out duration-150">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-slate-200 leading-5 rounded-lg hover:text-[#E72085] focus:outline-none focus:ring ring-[#E72085]/20 focus:border-[#E72085] active:bg-[#E72085]/5 active:text-[#E72085] transition ease-in-out duration-150">
                    Siguiente
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-slate-200 cursor-not-allowed leading-5 rounded-lg">
                    Siguiente
                </span>
            @endif

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-slate-500 leading-5">
                    Mostrando
                    @if ($paginator->firstItem())
                        <span class="font-bold text-slate-700">{{ $paginator->firstItem() }}</span>
                        a
                        <span class="font-bold text-slate-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    de
                    <span class="font-bold text-slate-700">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse shadow-sm rounded-lg">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Anterior">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-slate-200 cursor-not-allowed rounded-l-lg leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-l-lg leading-5 hover:text-[#E72085] focus:outline-none focus:ring ring-[#E72085]/20 focus:border-[#E72085] active:bg-[#E72085]/5 active:text-[#E72085] transition ease-in-out duration-150" aria-label="Anterior">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-500 bg-white border border-slate-200 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-[#E72085] border border-[#E72085] cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-slate-600 bg-white border border-slate-200 leading-5 hover:text-[#E72085] hover:border-[#E72085]/40 focus:outline-none focus:ring ring-[#E72085]/20 focus:border-[#E72085] active:bg-[#E72085]/5 active:text-[#E72085] transition ease-in-out duration-150" aria-label="Ir a la página {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-slate-500 bg-white border border-slate-200 rounded-r-lg leading-5 hover:text-[#E72085] focus:outline-none focus:ring ring-[#E72085]/20 focus:border-[#E72085] active:bg-[#E72085]/5 active:text-[#E72085] transition ease-in-out duration-150" aria-label="Siguiente">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Siguiente">
                            <span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-slate-200 cursor-not-allowed rounded-r-lg leading-5" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
