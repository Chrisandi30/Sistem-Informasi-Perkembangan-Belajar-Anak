<div>
    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered align-middle">
            <colgroup>
                <col style="width: 46%;">
                <col style="width: 24%;">
                <col style="width: 30%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Status</th>
                    <th class="text-nowrap pe-3">
                        <div class="d-flex justify-content-end">
                            <span style="display:inline-block; width:88px; text-align:left;">Aksi</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($tahunAjaran as $item)
                    <tr>
                        <td>{{ $item->tahun_ajaran }}</td>
                        <td>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap pe-3">
                            @if(! $item->is_active)
                                <button type="button" class="btn btn-sm btn-outline-success" wire:click="activateTahunAjaran({{ $item->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                            <a href="{{ route('admin.tahun-ajaran.edit', ['tahun_ajaran' => $item, 'return_to' => route('admin.tahun-ajaran.index', ['page' => $tahunAjaran->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click="deleteTahunAjaran({{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">{{ trim($search ?? '') !== '' ? 'Data yang dicari tidak ditemukan.' : 'Belum ada data tahun ajaran untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tahunAjaran->links() }}
</div>

