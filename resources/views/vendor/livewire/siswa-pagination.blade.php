@php
    // View: resources/views/vendor/livewire/siswa-pagination.blade.php
@endphp
@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation">
    <div class="d-flex align-items-center gap-1 flex-wrap justify-content-center">
        @if ($paginator->onFirstPage())
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 border text-muted" style="width:40px;height:40px;border-color:#e3e8f1;background:#f9fafb;">
                <i class="fas fa-chevron-left" style="font-size:12px;"></i>
            </span>
        @else
            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" wire:loading.attr="disabled" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#6b7280]" style="width:40px;height:40px;border-color:#e3e8f1;">
                <i class="fas fa-chevron-left" style="font-size:12px;"></i>
            </button>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#6b7280] fw-semibold" style="width:40px;height:40px;border-color:#e3e8f1;">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 border fw-bold text-[#2563eb]" style="width:40px;height:40px;border-color:#dbe7ff;background:#f8fbff;">
                            {{ $page }}
                        </span>
                    @else
                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#374151] fw-semibold" style="width:40px;height:40px;border-color:#e3e8f1;">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" wire:loading.attr="disabled" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#6b7280]" style="width:40px;height:40px;border-color:#e3e8f1;">
                <i class="fas fa-chevron-right" style="font-size:12px;"></i>
            </button>
        @else
            <span class="d-inline-flex align-items-center justify-content-center rounded-3 border text-muted" style="width:40px;height:40px;border-color:#e3e8f1;background:#f9fafb;">
                <i class="fas fa-chevron-right" style="font-size:12px;"></i>
            </span>
        @endif
    </div>
</nav>
@endif
