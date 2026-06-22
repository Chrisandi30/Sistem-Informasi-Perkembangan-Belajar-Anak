<div>
    @include('partials.styles.responsive-search-field')
    <div class="standard-search-wrap responsive-search-field relative mb-3" style="max-width: 200px;">
        <span class="pointer-events-none absolute left-[14px] top-1/2 z-[2] -translate-y-1/2 text-[14px] text-[#8a96ab]"><i class="fas fa-search"></i></span><input type="text" class="form-control pl-10" placeholder="Search" wire:model.live.debounce.300ms="search">
    </div>

    <div class="table-responsive">
        {{-- Tabel untuk menampilkan daftar data sistem. --}}
        <table class="table table-bordered align-middle akun-table-fit" style="table-layout:auto;">
            <colgroup>
                <col style="width: 32%;">
                <col style="width: 22%;">
                <col style="width: 16%;">
                <col style="width: 14%;">
                <col style="width: 18%;">
            </colgroup>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-start">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td style="min-width: 220px;">
                            <div>{{ $user->display_name ?? $user->name }}</div>
                            @if(!empty($user->secondary_name))
                                <small class="text-muted d-block mt-1">Orang Tua/Wali: {{ $user->secondary_name }}</small>
                            @endif
                        </td>
                        <td style="min-width: 160px; white-space: nowrap;">{{ $user->username }}</td>
                        <td style="min-width: 140px; white-space: nowrap;">
                            @php
                                $roleLabel = match ($user->role) {
                                    'orang_tua' => 'Orang Tua',
                                    'kepala_sekolah' => 'Kepala Sekolah',
                                    default => ucfirst(str_replace('_', ' ', $user->role)),
                                };
                            @endphp
                            {{ $roleLabel }}
                        </td>
                        <td style="min-width: 120px; white-space: nowrap;">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-start" style="min-width: 132px; white-space: nowrap;">
                            <a href="{{ route('admin.akun.edit', ['akun' => $user, 'return_to' => route('admin.akun.index', ['page' => $users->currentPage()])]) }}" wire:navigate class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            @if($user->role === 'admin')
                                <button type="button" class="btn btn-sm btn-outline-secondary opacity-50 cursor-not-allowed" disabled title="Akun admin tidak dapat dihapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete" wire:click="deleteAkun({{ $user->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">{{ $hasSearch ? 'Data yang dicari tidak ditemukan.' : 'Belum ada data akun untuk ditampilkan.' }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 mt-3">
        <div class="text-muted small">
            @if($users->total() > 0)
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            @else
                Showing 0 to 0 of 0 results
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="perPageAkun" class="text-muted small mb-0">Per page</label>
            <select id="perPageAkun" class="form-select form-select-sm" style="width: 86px;" wire:model.live="perPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div>
            {{ $users->links('vendor.livewire.siswa-pagination') }}
        </div>
    </div>
</div>
