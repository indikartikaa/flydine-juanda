@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col md:flex-row items-center justify-between gap-4 py-3 px-1">
        
        <!-- Info Summary Text -->
        <div class="text-xs sm:text-sm text-slate-500 font-semibold order-2 md:order-1 flex items-center gap-1.5 select-none">
            <span class="inline-block w-2 h-2 rounded-full bg-[#005ea2]/70 animate-pulse"></span>
            <span data-id="Menampilkan" data-en="Showing">Menampilkan</span>
            <span class="font-extrabold text-slate-800 bg-slate-100 border border-slate-200/60 px-2 py-0.5 rounded-md">{{ $paginator->firstItem() }} - {{ $paginator->lastItem() }}</span>
            <span data-id="dari" data-en="of">dari</span>
            <span class="font-extrabold text-[#005ea2]">{{ $paginator->total() }}</span>
            <span data-id="restoran" data-en="restaurants">restoran</span>
        </div>

        <!-- Pagination Controls Bar -->
        <div class="inline-flex items-center gap-1 sm:gap-1.5 p-1.5 bg-white/95 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/90 order-1 md:order-2">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-300 bg-slate-50/50 cursor-not-allowed select-none transition-colors" aria-disabled="true" aria-label="Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-link w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-600 hover:text-[#005ea2] hover:bg-blue-50/80 active:scale-95 transition-all duration-200 group" aria-label="Sebelumnya">
                    <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-7 sm:w-8 h-9 sm:h-10 flex items-center justify-center text-slate-400 font-bold select-none text-xs tracking-widest">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl bg-gradient-to-tr from-[#005ea2] via-[#0066b2] to-blue-500 text-white font-extrabold text-xs sm:text-sm shadow-md shadow-blue-500/25 scale-105 select-none transition-transform" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="pagination-link w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-600 hover:text-[#005ea2] hover:bg-blue-50/80 font-bold text-xs sm:text-sm active:scale-95 transition-all duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-link w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-600 hover:text-[#005ea2] hover:bg-blue-50/80 active:scale-95 transition-all duration-200 group" aria-label="Berikutnya">
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-300 bg-slate-50/50 cursor-not-allowed select-none transition-colors" aria-disabled="true" aria-label="Berikutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif

        </div>

    </nav>
@endif
