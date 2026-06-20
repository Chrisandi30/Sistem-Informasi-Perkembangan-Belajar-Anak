@php
    // View: resources/views/livewire/admin/siswa-table.blade.php
@endphp
<div>
    @php
        $siswaCellClass = '!px-[0.85rem] align-middle min-w-0 [overflow-wrap:anywhere] break-normal';
        $siswaActionButtonClass = 'btn btn-sm aksi-btn !inline-flex !h-[34px] !min-h-[34px] !w-[34px] !min-w-[34px] flex-none items-center justify-center rounded-[10px] !p-0 text-[0.78rem] leading-none';
    @endphp

    <div class="row g-3 mb-3 align-items-end">
        <div class="col-md-3">
            <div class="relative">
                <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="kelas_id">
                <option value="">Semua kelas</option>
                @foreach($kelasOptions as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive siswa-table-wrap overflow-x-auto">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered table-sm align-middle siswa-table w-full min-w-[760px] table-fixed">
            <colgroup>
                <col class="w-[18%]">
                <col class="w-[16%]">
                <col class="w-[17%]">
                <col class="w-[13%]">
                <col class="w-[13%]">
                <col class="w-[9%]">
                <col class="w-[14%]">
            </colgroup>
            <thead>
                <tr>
                    <th class="{{ $siswaCellClass }}">Nama Siswa</th>
                    <th class="{{ $siswaCellClass }}">NIS / NISN</th>
                    <th class="{{ $siswaCellClass }}">Tempat Tanggal Lahir</th>
                    <th class="{{ $siswaCellClass }}">Jenis Kelamin</th>
                    <th class="{{ $siswaCellClass }}">Tahun Ajaran</th>
                    <th class="{{ $siswaCellClass }} whitespace-nowrap">Kelas</th>
                    <th class="{{ $siswaCellClass }} !w-auto whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr>
                        <td class="{{ $siswaCellClass }}"><div>{{ $siswa->nama }}</div></td>
                        <td class="{{ $siswaCellClass }}">{{ ($siswa->nis ?: '-') . ' / ' . ($siswa->nisn ?: '-') }}</td>
                        <td class="{{ $siswaCellClass }}">
                            <div>{{ $siswa->tempat_lahir ?: '-' }}</div>
                            @if($siswa->tanggal_lahir)
                                <div>{{ optional($siswa->tanggal_lahir)->format('d-m-Y') }}</div>
                            @endif
                        </td>
                        <td class="{{ $siswaCellClass }}">{{ $siswa->jenis_kelamin_label }}</td>
                        <td class="{{ $siswaCellClass }}">{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                        <td class="{{ $siswaCellClass }} whitespace-nowrap">{{ $siswa->kelas->nama_kelas }}</td>
                        <td class="{{ $siswaCellClass }} !w-auto whitespace-nowrap">
                            <div class="flex flex-nowrap items-center justify-start gap-[6px]">
                                <a href="{{ route('admin.siswa.show', ['siswa' => $siswa, 'return_to' => route('admin.siswa.index', ['page' => $siswas->currentPage()])]) }}" wire:navigate class="{{ $siswaActionButtonClass }} btn-outline-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.siswa.edit', ['siswa' => $siswa, 'return_to' => route('admin.siswa.index', ['page' => $siswas->currentPage()])]) }}" wire:navigate class="{{ $siswaActionButtonClass }} btn-outline-primary"><i class="fas fa-pen"></i></a>
                                <button type="button" class="{{ $siswaActionButtonClass }} btn-outline-danger btn-delete" wire:click="deleteSiswa({{ $siswa->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada data siswa untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($siswas->total() > 0)
                Showing {{ $siswas->firstItem() }} to {{ $siswas->lastItem() }} of {{ $siswas->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPageSiswa" class="text-muted small mb-0">Per page</label>
            <select id="perPageSiswa" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $siswas->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
