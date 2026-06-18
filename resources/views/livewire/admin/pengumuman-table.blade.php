@php
    // View: resources/views/livewire/admin/pengumuman-table.blade.php
@endphp
<div>
    <style>
        .pengumuman-table th:nth-child(2),
        .pengumuman-table td:nth-child(2) {
            padding-left: 4rem !important;
        }
    </style>

    <div class="standard-search-wrap relative mb-3" style="max-width: 220px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle pengumuman-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal Terbit</th>
                    <th>Tanggal Berakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengumuman as $item)
                    <tr>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->tanggal_terbit->format('d-m-Y') }}</td>
                        <td>{{ $item->tanggal_berakhir?->format('d-m-Y') }}</td>
                        <td class="text-start">
                            <a href="{{ route('admin.pengumuman.edit', $item) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click="deletePengumuman({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada pengumuman untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($pengumuman->total() > 0)
                Showing {{ $pengumuman->firstItem() }} to {{ $pengumuman->lastItem() }} of {{ $pengumuman->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPagePengumuman" class="text-muted small mb-0">Per page</label>
            <select id="perPagePengumuman" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $pengumuman->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
