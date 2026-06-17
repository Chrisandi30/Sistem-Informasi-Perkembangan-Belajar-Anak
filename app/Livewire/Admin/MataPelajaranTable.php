<?php

namespace App\Livewire\Admin;

use App\Models\MataPelajaran;
use Livewire\Component;
use Livewire\WithPagination;

class MataPelajaranTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $perPage = 5;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function deleteMataPelajaran(int $id): void
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $mataPelajaran->delete();
        session()->flash('success', 'Mata pelajaran berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Mata pelajaran berhasil dihapus.');
    }

    public function render()
    {
        $search = trim($this->search);

        $mataPelajarans = MataPelajaran::with('kelas')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_mapel', 'like', "%{$search}%")
                        ->orWhereHas('kelas', fn ($kelasQuery) => $kelasQuery->where('nama_kelas', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.mata-pelajaran-table', [
            'mataPelajarans' => $mataPelajarans,
            'hasSearch' => $search !== '',
        ]);
    }
}

