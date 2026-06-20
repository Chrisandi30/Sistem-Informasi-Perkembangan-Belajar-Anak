<?php
namespace App\Livewire\Admin;

use App\Models\Pengumuman;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class PengumumanTable extends Component
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

    // Hapus pengumuman yang dipilih.
    public function deletePengumuman(int $id): void
    {
        Pengumuman::findOrFail($id)->delete();
        session()->flash('success', 'Pengumuman berhasil dihapus.');
        $this->dispatch('app-feedback', type: 'success', message: 'Pengumuman berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $search = trim($this->search);

        // Gunakan hanya kolom yang benar-benar tersedia agar search aman pada database hosting.
        $availableColumns = collect(Schema::getColumnListing('pengumuman'));
        $searchableColumns = collect([
            'judul',
            'isi',
            'tanggal_terbit',
            'tanggal_berakhir',
        ])->filter(fn ($column) => $availableColumns->contains($column))->values();

        $pengumuman = Pengumuman::query()
            ->when($search !== '' && $searchableColumns->isNotEmpty(), function ($query) use ($search, $searchableColumns) {
                $query->where(function ($subQuery) use ($search, $searchableColumns) {
                    foreach ($searchableColumns as $index => $column) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $subQuery->{$method}($column, 'like', "%{$search}%");
                    }
                });
            })
            ->when(
                $availableColumns->contains('tanggal_terbit'),
                fn ($query) => $query->latest('tanggal_terbit'),
                fn ($query) => $query->latest('id'),
            )
            ->paginate($this->perPage);

        return view('livewire.admin.pengumuman-table', [
            'pengumuman' => $pengumuman,
            'hasSearch' => $search !== '',
        ]);
    }
}

