<?php

namespace App\Livewire\Guru;

use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;

class SiswaTable extends Component
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

    public function render()
    {
        $guru = auth()->user()?->guru;
        abort_unless($guru, 403);

        $search = trim($this->search);

        $siswas = Siswa::with('kelas')
            ->where('kelas_id', $guru->kelas_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->orderBy('id')
            ->paginate($this->perPage);

        return view('livewire.guru.siswa-table', compact('siswas'));
    }
}
