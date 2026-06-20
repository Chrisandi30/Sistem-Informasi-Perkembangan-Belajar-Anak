<?php
namespace App\Livewire\Admin;

use App\Models\Guru;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GuruTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $perPage = 5;

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // Hapus data guru yang dipilih.
    public function deleteGuru(int $id): void
    {
        DB::transaction(function () use ($id) {
            $guru = Guru::with('user')->findOrFail($id);
            $user = $guru->user;

            $guru->delete();

            if ($user) {
                $user->delete();
            }
        });

        session()->flash('success', 'Data guru berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Data guru berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $search = trim($this->search);

        $gurus = Guru::with(['kelas', 'user'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('nuptk', 'like', "%{$search}%")
                        ->orWhere('jenjang_pendidikan', 'like', "%{$search}%")
                        ->orWhereHas('kelas', fn ($kelasQuery) => $kelasQuery->where('nama_kelas', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('username', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.guru-table', [
            'gurus' => $gurus,
            'hasSearch' => $search !== '',
        ]);
    }
}

