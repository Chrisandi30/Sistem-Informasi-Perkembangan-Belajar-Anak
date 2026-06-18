<div>
    <div class="row g-2 mb-3 align-items-end perkembangan-filters">
        <div class="standard-search-wrap col-md-3 relative perkembangan-search" style="max-width: 220px;">
            <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
        </div>
        <div class="col-md-3">
            <select wire:model.live="kelas_id" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model.live="bulan" class="form-select">
                <option value="">Semua Bulan</option>
                @foreach($monthOptions as $monthNumber => $monthName)
                    <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select wire:model.live="tahun" class="form-select">
                <option value="">Semua Tahun</option>
                @foreach($yearOptions as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive perkembangan-table-wrap">
        <table class="table table-bordered table-sm align-middle perkembangan-list-table">
            <colgroup>
                <col style="width: 28%;">
                <col style="width: 18%;">
                <col style="width: 18%;">
                <col style="width: 20%;">
                <col style="width: 16%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Bulan / Tahun</th>
                    <th>Guru</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perkembangans as $p)
                    <tr>
                        <td>{{ $p->siswa->nama }}</td>
                        <td>{{ $p->kelas->nama_kelas ?? ($p->guru->kelas->nama_kelas ?? '-') }}</td>
                        <td>{{ $monthOptions[$p->bulan] ?? $p->bulan }} / {{ $p->tahun }}</td>
                        <td>{{ $p->guru->nama }}</td>
                        <td>
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.perkembangan.export-single', $p) }}" class="btn btn-outline-danger btn-sm pdf-download">PDF</a>
                                <a href="{{ route('admin.perkembangan.print', $p) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm pdf-download" aria-label="Cetak" title="Cetak">
                                    <i class="fas fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada laporan perkembangan untuk ditampilkan.' }}</td></tr>
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
            <label for="perPagePerkembangan" class="text-muted small mb-0">Per page</label>
            <select id="perPagePerkembangan" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
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

