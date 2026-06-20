<?php

// Livewire: app/Livewire/Admin/TahunAjaranTable.php

namespace App\Livewire\Admin;

use App\Models\TahunAjaran;
use Livewire\Component;
use Livewire\WithPagination;

class TahunAjaranTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    // Tangani proses activate tahun ajaran.
    public function activateTahunAjaran(int $id): void
    {
        TahunAjaran::query()->update(['is_active' => false]);
        TahunAjaran::findOrFail($id)->update(['is_active' => true]);

        session()->flash('success', 'Tahun ajaran aktif berhasil diperbarui.');
    }

    // Tangani proses delete tahun ajaran.
    public function deleteTahunAjaran(int $id): void
    {
        TahunAjaran::findOrFail($id)->delete();
        session()->flash('success', 'Data tahun ajaran berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Data tahun ajaran berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $tahunAjaran = TahunAjaran::query()
            ->latest()
            ->paginate(10);

        return view('livewire.admin.tahun-ajaran-table', compact('tahunAjaran'));
    }
}

