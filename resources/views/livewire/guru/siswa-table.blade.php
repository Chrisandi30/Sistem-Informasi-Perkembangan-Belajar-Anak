<div>
    <div class="relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute start-0 top-50 z-[2] translate-middle-y ms-3 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span>
        <input type="text" class="form-control" style="padding-left: 44px;" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                    <tr>
                        <td>{{ $siswa->nama }}</td>
                        <td>
                            <a href="{{ route('guru.perkembangan.create', ['siswa' => $siswa->id]) }}" wire:navigate class="btn btn-save btn-sm">
                                <i class="fas fa-file-pen me-1"></i> Isi Laporan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Data siswa tidak ditemukan.</td></tr>
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
            <label for="perPageGuruSiswa" class="text-muted small mb-0">Per page</label>
            <select id="perPageGuruSiswa" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
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