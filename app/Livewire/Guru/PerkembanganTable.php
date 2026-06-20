<?php
namespace App\Livewire\Guru;

use App\Models\Perkembangan;
use App\Models\Siswa;
use Livewire\Component;
use Livewire\WithPagination;

class PerkembanganTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $siswaId = '';
    public string $bulan = '';
    public string $tahun = '';
    public int $perPage = 5;

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingSiswaId(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingBulan(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingTahun(): void
    {
        $this->resetPage();
    }

    // Kembalikan pagination ke halaman pertama saat filter berubah.
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // Hapus laporan perkembangan yang dipilih.
    public function deletePerkembangan(int $id): void
    {
        $guru = auth()->user()?->guru;
        abort_unless($guru, 403);

        $perkembangan = Perkembangan::findOrFail($id);
        abort_if($perkembangan->guru_id !== $guru->id, 403);

        $perkembangan->delete();

        session()->flash('success', 'Laporan berhasil dihapus.');
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        $guru = auth()->user()?->guru;
        abort_unless($guru, 403);

        $monthOptions = $this->monthOptions();
        $search = trim($this->search);
        $matchingMonths = collect($monthOptions)
            ->filter(fn ($monthName) => $search !== '' && str_contains(strtolower($monthName), strtolower($search)))
            ->keys()
            ->all();

        $query = Perkembangan::with('siswa')
            ->where('guru_id', $guru->id)
            ->where('kelas_id', $guru->kelas_id)
            ->whereHas('siswa', fn ($siswaQuery) => $siswaQuery->where('kelas_id', $guru->kelas_id))
            ->when($this->siswaId !== '', fn ($builder) => $builder->where('siswa_id', (int) $this->siswaId))
            ->when($this->bulan !== '', fn ($builder) => $builder->where('bulan', (int) $this->bulan))
            ->when($this->tahun !== '', fn ($builder) => $builder->where('tahun', (int) $this->tahun))
            ->when($search !== '', function ($builder) use ($search, $matchingMonths) {
                $builder->where(function ($subQuery) use ($search, $matchingMonths) {
                    $subQuery->whereHas('siswa', fn ($siswaQuery) => $siswaQuery->where('nama', 'like', "%{$search}%"))
                        ->orWhere('tahun', 'like', "%{$search}%");

                    if (!empty($matchingMonths)) {
                        $subQuery->orWhereIn('bulan', $matchingMonths);
                    }
                });
            })
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $perkembangans = $query->paginate($this->perPage);
        $siswas = Siswa::where('kelas_id', $guru->kelas_id)->orderBy('nama')->get();
        $yearOptions = $this->yearOptions(
            Perkembangan::where('guru_id', $guru->id)->distinct()->orderByDesc('tahun')->pluck('tahun')->all()
        );

        return view('livewire.guru.perkembangan-table', compact('perkembangans', 'siswas', 'monthOptions', 'yearOptions'));
    }

    // Sediakan daftar nama bulan untuk filter.
    private function monthOptions(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }

    // Sediakan daftar tahun untuk filter.
    private function yearOptions(array $years = []): array
    {
        $currentYear = now()->year;
        $baseYears = [$currentYear - 1, $currentYear, $currentYear + 1];

        return collect(array_merge($baseYears, $years))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();
    }
}
