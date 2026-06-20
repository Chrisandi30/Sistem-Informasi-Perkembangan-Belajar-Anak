<?php
namespace App\Livewire\Admin;

use App\Models\Kelas;
use Illuminate\Database\QueryException;
use Livewire\Component;
use Livewire\WithPagination;

class KelasTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Hapus data kelas yang dipilih.
    public function deleteKelas(int $id): void
    {
        try {
            Kelas::findOrFail($id)->delete();
            session()->flash('success', 'Data kelas berhasil dihapus.');
            $this->dispatch('app-feedback', type: 'success', message: 'Data kelas berhasil dihapus.');
        } catch (QueryException $exception) {
            session()->flash('error', 'Data kelas tidak dapat dihapus karena masih dipakai data lain.');
            $this->dispatch('app-feedback', type: 'error', message: 'Data kelas tidak dapat dihapus karena masih dipakai data lain.');
        }
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $search = trim($this->search);

        $kelas = Kelas::when($search !== '', fn ($query) => $query->where('nama_kelas', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.kelas-table', [
            'kelas' => $kelas,
            'hasSearch' => $search !== '',
        ]);
    }
}

