<?php

namespace App\Livewire\KepalaSekolah;

use App\Models\Kelas;
use App\Models\Perkembangan;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public int $perPage = 5;

    public string $status = '';
    public ?int $kelas_id = null;
    public ?int $bulan = null;
    public ?int $tahun = null;

    public function mount(): void
    {
        // Default: tampilkan laporan periode berjalan, tanpa mengunci status.
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->status = '';
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
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

    public function render()
    {
        $search = trim($this->search);

        $query = Perkembangan::with(['siswa.kelas', 'guru.kelas', 'kelas', 'validator'])
            ->latest('tahun')
            ->latest('bulan');

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        if ($this->bulan) {
            $query->where('bulan', $this->bulan);
        }

        if ($this->tahun) {
            $query->where('tahun', $this->tahun);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', fn ($sq) => $sq->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('guru', fn ($gq) => $gq->where('nama', 'like', "%{$search}%"));
            });
        }

        $perkembangans = $query->paginate($this->perPage);

        $kelasOptions = Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        $monthOptions = [
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

        $currentYear = now()->year;
        $yearOptions = [$currentYear - 1, $currentYear, $currentYear + 1];

        $statusOptions = [
            '' => 'Semua Status',
            'menunggu' => 'Menunggu',
            'revisi' => 'Revisi',
            'disetujui' => 'Disetujui',
        ];

        return view('livewire.kepala-sekolah.review-table', compact(
            'perkembangans',
            'kelasOptions',
            'monthOptions',
            'yearOptions',
            'statusOptions',
            'search'
        ));
    }
}
