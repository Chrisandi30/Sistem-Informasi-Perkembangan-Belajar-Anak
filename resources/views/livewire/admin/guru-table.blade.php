<div>
    @include('partials.styles.responsive-search-field')
    @php
        $guruCellClass = '!px-4 align-middle';
        $guruActionButtonClass = 'btn btn-sm aksi-btn !inline-flex !h-[34px] !min-h-[34px] !w-[34px] !min-w-[34px] flex-none items-center justify-center rounded-[10px] !p-0 text-[0.78rem] leading-none';
    @endphp

    <div class="standard-search-wrap responsive-search-field relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered table-sm align-middle guru-table w-full min-w-0 table-fixed">
            <colgroup>
                <col class="w-[19%]">
                <col class="w-[16%]">
                <col class="w-[13%]">
                <col class="w-[17%]">
                <col class="w-[16%]">
                <col class="w-[8%]">
                <col class="w-[11%]">
            </colgroup>
            <thead>
                <tr>
                    <th class="{{ $guruCellClass }} min-w-[170px] whitespace-normal break-normal text-left [overflow-wrap:normal]">Nama</th>
                    <th class="{{ $guruCellClass }} min-w-0 whitespace-nowrap text-left">NUPTK</th>
                    <th class="{{ $guruCellClass }} whitespace-nowrap text-left">Jenis Kelamin</th>
                    <th class="{{ $guruCellClass }} text-center whitespace-nowrap">Jenjang Pendidikan</th>
                    <th class="{{ $guruCellClass }} !pl-6 break-words text-left">Alamat</th>
                    <th class="{{ $guruCellClass }} whitespace-nowrap text-center">Kelas</th>
                    <th class="{{ $guruCellClass }} min-w-0 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
                    <tr>
                        <td class="{{ $guruCellClass }} min-w-[170px] whitespace-normal break-normal text-left [overflow-wrap:normal]">{{ $guru->nama }}</td>
                        <td class="{{ $guruCellClass }} min-w-0 whitespace-nowrap text-left">{{ $guru->nuptk ?: '-' }}</td>
                        <td class="{{ $guruCellClass }} whitespace-nowrap text-left">{{ $guru->jenis_kelamin_label }}</td>
                        <td class="{{ $guruCellClass }} text-center break-words">{{ $guru->jenjang_pendidikan }}</td>
                        <td class="{{ $guruCellClass }} !pl-6 break-words text-left leading-[1.45]">{{ $guru->alamat ?: '-' }}</td>
                        <td class="{{ $guruCellClass }} whitespace-nowrap text-center">{{ $guru->kelas->nama_kelas }}</td>
                        <td class="{{ $guruCellClass }} min-w-0 whitespace-nowrap">
                            <div class="flex flex-nowrap items-center justify-center gap-[8px]">
                                <a href="{{ route('admin.guru.edit', ['guru' => $guru, 'return_to' => route('admin.guru.index', ['page' => $gurus->currentPage()])]) }}" wire:navigate class="{{ $guruActionButtonClass }} btn-outline-primary"><i class="fas fa-pen"></i></a>
                                <button type="button" class="{{ $guruActionButtonClass }} btn-outline-danger btn-delete" wire:click="deleteGuru({{ $guru->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada data guru untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($gurus->total() > 0)
                Showing {{ $gurus->firstItem() }} to {{ $gurus->lastItem() }} of {{ $gurus->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPageGuru" class="text-muted small mb-0">Per page</label>
            <select id="perPageGuru" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $gurus->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
