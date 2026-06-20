<div>
    <div class="row g-2 mb-3">
        <div class="standard-search-wrap col-md-3 relative" style="max-width: 200px;">
            <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="bulan">
                <option value="">Semua Bulan</option>
                @foreach($monthOptions as $monthNumber => $monthName)
                    <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="tahun">
                <option value="">Semua Tahun</option>
                @foreach($yearOptions as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered table-sm align-middle">
            <colgroup>
                <col style="width: 38%;">
                <col style="width: 18%;">
                <col style="width: 18%;">
                <col style="width: 26%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Bulan / Tahun</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perkembangans as $item)
                    @php
                        $statusLabel = match ($item->status) {
                            'disetujui' => 'Disetujui',
                            'revisi' => 'Revisi',
                            default => 'Menunggu',
                        };
                        $statusBadge = match ($item->status) {
                            'disetujui' => 'bg-success',
                            'revisi' => 'bg-danger',
                            default => 'bg-warning text-dark',
                        };
                    @endphp
                    <tr>
                        <td>{{ $item->siswa->nama }}</td>
                        <td>{{ $monthOptions[$item->bulan] ?? $item->bulan }} / {{ $item->tahun }}</td>
                        <td>
                            <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="text-start">
                            <a href="{{ route('guru.perkembangan.show', ['perkembangan' => $item, 'return_to' => route('guru.perkembangan.index', ['page' => $perkembangans->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('guru.perkembangan.edit', ['perkembangan' => $item, 'return_to' => route('guru.perkembangan.index', ['page' => $perkembangans->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="{{ $item->status === 'disetujui' ? 'Edit akan mengirim ulang untuk validasi' : 'Edit' }}">
                                <i class="fas fa-pen"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click="deletePerkembangan({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Laporan perkembangan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($perkembangans->total() > 0)
                Showing {{ $perkembangans->firstItem() }} to {{ $perkembangans->lastItem() }} of {{ $perkembangans->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPageGuruPerkembangan" class="text-muted small mb-0">Per page</label>
            <select id="perPageGuruPerkembangan" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $perkembangans->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
