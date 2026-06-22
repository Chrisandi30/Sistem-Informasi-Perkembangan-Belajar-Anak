<div>
    @include('partials.styles.responsive-search-field')
    <div class="standard-search-wrap responsive-search-field relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $item)
                    <tr>
                        <td>{{ $item->nama_kelas }}</td>
                        <td class="text-start">
                            <a href="{{ route('admin.kelas.edit', ['kela' => $item, 'return_to' => route('admin.kelas.index', ['page' => $kelas->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click="deleteKelas({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada data kelas untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $kelas->links() }}
</div>
