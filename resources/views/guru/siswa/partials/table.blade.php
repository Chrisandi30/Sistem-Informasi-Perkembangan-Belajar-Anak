@php
    // View: resources/views/guru/siswa/partials/table.blade.php
@endphp
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
                        <a href="{{ route('guru.perkembangan.create', [
                            'siswa' => $siswa->id,
                            'return_to' => route('guru.siswa.index', array_filter([
                                'search' => $search,
                                'per_page' => $perPage,
                                'page' => $siswas->currentPage(),
                            ], fn ($value) => $value !== null && $value !== '')),
                        ]) }}" wire:navigate class="btn btn-save btn-sm">
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

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-4">
    <div class="text-muted small">
        @if($siswas->total() > 0)
            Showing {{ $siswas->firstItem() }} to {{ $siswas->lastItem() }} of {{ $siswas->total() }} results
        @else
            Showing 0 to 0 of 0 results
        @endif
    </div>

    <form method="get" action="{{ route('guru.siswa.index') }}" class="d-flex align-items-center gap-2">
        <input type="hidden" name="search" value="{{ $search }}">
        <label for="perPageGuruSiswa" class="text-muted small mb-0">Per page</label>
        <select id="perPageGuruSiswa" name="per_page" class="form-select form-select-sm" style="width: 86px;">
            @foreach([5, 10, 25, 50] as $option)
                <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
            @endforeach
        </select>
    </form>

    <div class="d-flex justify-content-center">
        @if($siswas->hasPages())
            <nav role="navigation" aria-label="Pagination Navigation" class="pagination mb-0">
                <div class="d-flex align-items-center gap-1 flex-wrap justify-content-center">
                    @if($siswas->onFirstPage())
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 border text-muted" style="width:40px;height:40px;border-color:#e3e8f1;background:#f9fafb;">
                            <i class="fas fa-chevron-left" style="font-size:12px;"></i>
                        </span>
                    @else
                        <a href="{{ $siswas->previousPageUrl() }}" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#6b7280] no-loading" style="width:40px;height:40px;border-color:#e3e8f1;">
                            <i class="fas fa-chevron-left" style="font-size:12px;"></i>
                        </a>
                    @endif

                    @foreach($siswas->getUrlRange(1, $siswas->lastPage()) as $pageNumber => $url)
                        @if($pageNumber === $siswas->currentPage())
                            <span class="d-inline-flex align-items-center justify-content-center rounded-3 border fw-bold text-[#2563eb]" style="width:40px;height:40px;border-color:#dbe7ff;background:#f8fbff;">
                                {{ $pageNumber }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#374151] fw-semibold no-loading" style="width:40px;height:40px;border-color:#e3e8f1;">
                                {{ $pageNumber }}
                            </a>
                        @endif
                    @endforeach

                    @if($siswas->hasMorePages())
                        <a href="{{ $siswas->nextPageUrl() }}" class="btn btn-sm d-inline-flex align-items-center justify-content-center rounded-3 border bg-white text-[#6b7280] no-loading" style="width:40px;height:40px;border-color:#e3e8f1;">
                            <i class="fas fa-chevron-right" style="font-size:12px;"></i>
                        </a>
                    @else
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 border text-muted" style="width:40px;height:40px;border-color:#e3e8f1;background:#f9fafb;">
                            <i class="fas fa-chevron-right" style="font-size:12px;"></i>
                        </span>
                    @endif
                </div>
            </nav>
        @endif
    </div>
</div>