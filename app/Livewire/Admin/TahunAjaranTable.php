<?php

namespace App\Livewire\Admin;

use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;

class TahunAjaranTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public function activateTahunAjaran(int $id): void
    {
        TahunAjaran::query()->update(['is_active' => false]);
        TahunAjaran::findOrFail($id)->update(['is_active' => true]);

        session()->flash('success', 'Tahun ajaran aktif berhasil diperbarui.');
    }

    public function deleteTahunAjaran(int $id): void
    {
        TahunAjaran::findOrFail($id)->delete();
        session()->flash('success', 'Data tahun ajaran berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Data tahun ajaran berhasil dihapus.');
    }

    public function render()
    {
        $tahunAjaran = TahunAjaran::query()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.tahun-ajaran-table', compact('tahunAjaran'));
    }
}

