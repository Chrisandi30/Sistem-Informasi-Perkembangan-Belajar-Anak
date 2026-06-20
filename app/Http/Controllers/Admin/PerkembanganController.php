<?php

// Controller: app/Http/Controllers/Admin/PerkembanganController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Perkembangan;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PerkembanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Perkembangan::with(['siswa.kelas', 'siswa.tahunAjaran', 'guru.kelas', 'kelas', 'detailPerkembangans'])
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->integer('kelas_id'));
        }

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->integer('siswa_id'));
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->integer('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->integer('tahun'));
        }

        $perkembangans = $query->paginate(12)->withQueryString();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $siswas = Siswa::orderBy('nama')->get();
        $monthOptions = $this->monthOptions();
        $yearOptions = $this->yearOptions(
            Perkembangan::distinct()->orderBy('tahun')->pluck('tahun')->all()
        );

        return view('admin.perkembangan.index', compact('perkembangans', 'kelas', 'siswas', 'monthOptions', 'yearOptions'));
    }

    public function exportByPerkembangan(Perkembangan $perkembangan)
    {
        $perkembangan->load(['siswa.kelas', 'siswa.tahunAjaran', 'guru.kelas', 'kelas', 'detailPerkembangans']);

        $pdf = Pdf::loadView('admin.perkembangan.pdf', [
            'perkembangan' => $perkembangan,
            'printedAt' => now(),
            'monthOptions' => $this->monthOptions(),
        ])->setPaper('a4', 'portrait');

        $namaSiswa = preg_replace('~[\\\\/:*?"<>|]+~', '', $perkembangan->siswa->nama);
        $bulan = $this->monthOptions()[$perkembangan->bulan] ?? $perkembangan->bulan;
        $filename = 'Laporan Perkembangan ' . $namaSiswa . ' ' . $bulan . ' ' . $perkembangan->tahun . '.pdf';

        return $pdf->download($filename);
    }

    public function printByPerkembangan(Perkembangan $perkembangan)
    {
        $perkembangan->load(['siswa.kelas', 'siswa.tahunAjaran', 'guru.kelas', 'kelas', 'detailPerkembangans']);

        return view('admin.perkembangan.print', [
            'perkembangan' => $perkembangan,
            'printedAt' => now(),
            'monthOptions' => $this->monthOptions(),
        ]);
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

    private function yearOptions(array $years = []): array
    {
        $allYears = range(2020, 2100);

        return collect(array_merge($years, $allYears))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
