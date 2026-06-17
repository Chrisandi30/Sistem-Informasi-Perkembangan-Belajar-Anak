<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\Perkembangan;
use Livewire\Component;
use Livewire\WithPagination;

class PerkembanganTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $kelas_id = '';
    public string $bulan = '';
    public string $tahun = '';
    public int $perPage = 5;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKelasId(): void
    {
        $this->resetPage();
    }

    public function updatingBulan(): void
    {
        $this->resetPage();
    }

    public function updatingTahun(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

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

    private function yearOptions(): array
    {
        $currentYear = now()->year;
        $years = Perkembangan::distinct()->orderByDesc('tahun')->pluck('tahun')->all();
        $baseYears = [$currentYear - 1, $currentYear, $currentYear + 1];

        return collect(array_merge($baseYears, $years))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();
    }

    public function render()
    {
        $search = trim($this->search);

        $perkembangans = Perkembangan::with(['siswa', 'guru.kelas', 'kelas'])
            ->when($this->kelas_id !== '', function ($query) {
                $query->where('kelas_id', (int) $this->kelas_id);
            })
            ->when($this->bulan !== '', fn ($query) => $query->where('bulan', (int) $this->bulan))
            ->when($this->tahun !== '', fn ($query) => $query->where('tahun', (int) $this->tahun))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('siswa', function ($siswaQuery) use ($search) {
                        $siswaQuery->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('kelas', fn ($kelasQuery) => $kelasQuery->where('nama_kelas', 'like', "%{$search}%"));
                    })->orWhereHas('guru', fn ($guruQuery) => $guruQuery->where('nama', 'like', "%{$search}%"));
                });
            })
            ->latest('tahun')
            ->latest('bulan')
            ->paginate($this->perPage);

        return view('livewire.admin.perkembangan-table', [
            'perkembangans' => $perkembangans,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'monthOptions' => $this->monthOptions(),
            'yearOptions' => $this->yearOptions(),
            'hasSearch' => $search !== '',
        ]);
    }
}
