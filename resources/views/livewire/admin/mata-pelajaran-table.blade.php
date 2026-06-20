@php
    // View: resources/views/livewire/admin/mata-pelajaran-table.blade.php
@endphp
<div>
    <div class="standard-search-wrap relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Nama Mata Pelajaran</th>
                    <th class="ps-5">Kelas</th>
                    <th class="ps-5">Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mataPelajarans as $item)
                    <tr>
                        <td>{{ $item->nama_mapel }}</td>
                        <td class="ps-5">{{ $item->kelas->nama_kelas }}</td>
                        <td class="ps-5">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-start text-nowrap">
                            <a href="{{ route('admin.mata-pelajaran.edit', ['mata_pelajaran' => $item, 'return_to' => route('admin.mata-pelajaran.index', ['page' => $mataPelajarans->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click.prevent="deleteMataPelajaran({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada mata pelajaran untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($mataPelajarans->total() > 0)
                Showing {{ $mataPelajarans->firstItem() }} to {{ $mataPelajarans->lastItem() }} of {{ $mataPelajarans->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPageMataPelajaran" class="text-muted small mb-0">Per page</label>
            <select id="perPageMataPelajaran" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $mataPelajarans->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
