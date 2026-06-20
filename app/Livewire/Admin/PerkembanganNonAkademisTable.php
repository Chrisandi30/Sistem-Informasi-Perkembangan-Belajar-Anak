<?php

// Livewire: app/Livewire/Admin/PerkembanganNonAkademisTable.php

namespace App\Livewire\Admin;

use App\Models\PerkembanganNonAkademis;
use Livewire\Component;
use Livewire\WithPagination;

class PerkembanganNonAkademisTable extends Component
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

    // Tangani proses delete perkembangan non akademis.
    public function deletePerkembanganNonAkademis(int $id): void
    {
        $item = PerkembanganNonAkademis::findOrFail($id);
        $item->delete();

        session()->flash('success', 'Aspek perkembangan non akademis berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Aspek perkembangan non akademis berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $search = trim($this->search);

        $items = PerkembanganNonAkademis::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('kategori_aspek', 'like', "%{$search}%")
                        ->orWhere('nama_aspek', 'like', "%{$search}%");
                });
            })
            ->orderBy('urutan')
            ->orderBy('kategori_aspek')
            ->orderBy('nama_aspek')
            ->paginate($this->perPage);

        return view('livewire.admin.perkembangan-non-akademis-table', [
            'items' => $items,
            'hasSearch' => $search !== '',
        ]);
    }
}
