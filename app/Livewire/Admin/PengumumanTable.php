<?php

namespace App\Livewire\Admin;

use App\Models\Pengumuman;
use Livewire\Component;
use Livewire\WithPagination;

class PengumumanTable extends Component
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

    public function deletePengumuman(int $id): void
    {
        Pengumuman::findOrFail($id)->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Pengumuman berhasil dihapus.');
    }

    public function render()
    {
        $search = trim($this->search);

        $pengumuman = Pengumuman::when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%")
                        ->orWhere('tanggal_terbit', 'like', "%{$search}%")
                        ->orWhere('tanggal_mulai', 'like', "%{$search}%")
                        ->orWhere('tanggal_berakhir', 'like', "%{$search}%");
                });
            })
            ->latest('tanggal_terbit')
            ->paginate($this->perPage);

        return view('livewire.admin.pengumuman-table', [
            'pengumuman' => $pengumuman,
            'hasSearch' => $search !== '',
        ]);
    }
}

