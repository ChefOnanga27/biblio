{{-- Pagination du site : $paginator->links('partials.pagination') --}}
@if ($paginator->total() > 0)
<nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-ligne">

    <p class="text-[13px] text-brume">
        <span class="font-semibold text-encre">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-encre">{{ $paginator->lastItem() }}</span>
        sur <span class="font-semibold text-encre">{{ $paginator->total() }}</span>
    </p>

    @if ($paginator->hasPages())
        @php
            $base = 'min-w-10 h-10 px-3.5 inline-flex items-center justify-center rounded-full text-[14px] font-medium transition-colors';
            $lien = $base . ' bg-surface-2 border border-white/5 text-brume hover:text-encre hover:border-white/20';
            $inactif = $base . ' border border-white/5 text-voile cursor-default';
        @endphp
        <ul class="flex items-center gap-1.5">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="{{ $inactif }}" aria-disabled="true">‹ <span class="hidden sm:inline ml-1">Précédent</span></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $lien }}">‹ <span class="hidden sm:inline ml-1">Précédent</span></a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="hidden sm:block"><span class="{{ $base }} text-voile">{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="{{ $page == $paginator->currentPage() ? '' : 'hidden sm:block' }}">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="{{ $base }} bg-corail text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $lien }}" aria-label="Page {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $lien }}"><span class="hidden sm:inline mr-1">Suivant</span> ›</a>
                @else
                    <span class="{{ $inactif }}" aria-disabled="true"><span class="hidden sm:inline mr-1">Suivant</span> ›</span>
                @endif
            </li>
        </ul>
    @endif
</nav>
@endif
