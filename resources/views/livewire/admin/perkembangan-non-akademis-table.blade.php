<div>
    @include('partials.styles.responsive-search-field')
    @php
        $nonAkademisActionButtonClass = 'btn btn-sm aksi-btn !inline-flex !h-[34px] !min-h-[34px] !w-[34px] !min-w-[34px] flex-none items-center justify-center rounded-[10px] !p-0 text-[0.78rem] leading-none';
    @endphp

    <div class="standard-search-wrap responsive-search-field relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive non-akademis-table-wrap">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered align-middle non-akademis-table min-w-[680px] table-fixed">
            <thead>
                <tr>
                    <th>Kategori Aspek</th>
                    <th class="ps-5">Nama Aspek</th>
                    <th class="text-center">Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->kategori_aspek }}</td>
                        <td class="ps-5">{{ $item->nama_aspek }}</td>
                        <td class="text-center">{{ $item->urutan }}</td>
                        <td>
                            <span class="non-akademis-status inline-flex whitespace-nowrap break-keep rounded-full px-3 py-1 text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-start text-nowrap non-akademis-aksi-cell">
                            <div class="flex flex-nowrap items-center justify-start gap-[6px]">
                                <a href="{{ route('admin.perkembangan-non-akademis.edit', ['perkembangan_non_akademi' => $item, 'return_to' => route('admin.perkembangan-non-akademis.index', ['page' => $items->currentPage()])]) }}" wire:navigate class="{{ $nonAkademisActionButtonClass }} btn-outline-primary"><i class="fas fa-pen"></i></a>
                                <button type="button" class="{{ $nonAkademisActionButtonClass }} btn-outline-danger btn-delete" wire:click.prevent="deletePerkembanganNonAkademis({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada aspek non akademis untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($items->total() > 0)
                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPagePerkembanganNonAkademis" class="text-muted small mb-0">Per page</label>
            <select id="perPagePerkembanganNonAkademis" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $items->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
