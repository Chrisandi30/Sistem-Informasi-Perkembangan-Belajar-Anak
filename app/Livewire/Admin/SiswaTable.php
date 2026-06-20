<?php

// Livewire: app/Livewire/Admin/SiswaTable.php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class SiswaTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $kelas_id = '';
    public int $perPage = 5;

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingKelasId(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // Hapus siswa beserta data terkait yang mengikuti relasi.
    public function deleteSiswa(int $id): void
    {
        DB::transaction(function () use ($id) {
            $siswa = Siswa::with('user')->findOrFail($id);
            $user = $siswa->user;

            if ($siswa->pas_foto) {
                Storage::disk('public')->delete($siswa->pas_foto);
            }

            $siswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        session()->flash('success', 'Data siswa berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Data siswa berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $search = trim($this->search);

        $siswas = Siswa::with(['kelas', 'tahunAjaran', 'user'])
            ->when($this->kelas_id !== '', fn ($query) => $query->where('kelas_id', $this->kelas_id))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('tempat_lahir', 'like', "%{$search}%")
                        ->orWhere('nama_ayah', 'like', "%{$search}%")
                        ->orWhere('nama_ibu', 'like', "%{$search}%")
                        ->orWhere('nama_wali', 'like', "%{$search}%")
                        ->orWhere('nomor_telepon', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhereHas('kelas', fn ($kelasQuery) => $kelasQuery->where('nama_kelas', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $kelasOptions = Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        return view('livewire.admin.siswa-table', [
            'siswas' => $siswas,
            'kelasOptions' => $kelasOptions,
            'hasSearch' => $search !== '',
        ]);
    }
}


