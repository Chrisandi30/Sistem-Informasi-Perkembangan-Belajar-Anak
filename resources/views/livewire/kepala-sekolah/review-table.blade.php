<div>
    <div class="review-filters mb-4 mt-1" style="display:grid; grid-template-columns:minmax(240px,1.5fr) repeat(4,minmax(140px,1fr)); gap:12px; align-items:end;">
        <div class="review-filter review-filter-search responsive-search-field" style="position:relative; min-width:0;">
            <span class="pointer-events-none" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); z-index:2; font-size:14px; color:#8a96ab;">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" style="padding-left: 44px;" placeholder="Search" wire:model.live.debounce.300ms="search">
        </div>
        <div class="review-filter" style="min-width:0;">
            <select wire:model.live="kelas_id" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach($kelasOptions as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="review-filter" style="min-width:0;">
            <select wire:model.live="bulan" class="form-select">
                <option value="">Semua Bulan</option>
                @foreach($monthOptions as $monthNumber => $monthName)
                    <option value="{{ $monthNumber }}">{{ $monthName }}</option>
                @endforeach
            </select>
        </div>
        <div class="review-filter" style="min-width:0;">
            <select wire:model.live="tahun" class="form-select">
                <option value="">Semua Tahun</option>
                @foreach($yearOptions as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div class="review-filter" style="min-width:0;">
            <select wire:model.live="status" class="form-select">
                @foreach($statusOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-responsive mt-2 review-table-wrap">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered table-sm align-middle review-list-table">
            <colgroup>
                <col style="width: 26%;">
                <col style="width: 12%;">
                <col style="width: 14%;">
                <col style="width: 18%;">
                <col style="width: 12%;">
                <col style="width: 6%;">
            </colgroup>
            <thead>
            <tr>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Bulan / Tahun</th>
                <th>Guru</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($perkembangans as $p)
                @php
                    $label = match ($p->status) {
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => 'Menunggu',
                    };
                    $badge = match ($p->status) {
                        'disetujui' => 'bg-success',
                        'revisi' => 'bg-danger',
                        default => 'bg-warning text-dark',
                    };
                @endphp
                <tr>
                    <td>{{ $p->siswa?->nama ?? '-' }}</td>
                    <td>{{ $p->kelas?->nama_kelas ?? $p->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                    <td>{{ $monthOptions[$p->bulan] ?? $p->bulan }} / {{ $p->tahun }}</td>
                    <td>{{ $p->guru?->nama ?? '-' }}</td>
                    <td><span class="badge {{ $badge }}">{{ $label }}</span></td>
                    <td class="text-start">
                        <a href="{{ route('kepala-sekolah.review.show', ['perkembangan' => $p, 'return_to' => route('kepala-sekolah.review.index', ['page' => $perkembangans->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-info" aria-label="Detail" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
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
            <label class="text-muted small mb-0">Per page</label>
            <select class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
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
