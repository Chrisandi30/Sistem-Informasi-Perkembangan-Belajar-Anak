<?php

// Controller: app/Http/Controllers/OrangTua/LaporanController.php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Perkembangan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LaporanController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        if (! $siswa) {
            return redirect()->route('orang-tua.profil.index')->withErrors([
                'Akun orang tua ini belum terhubung ke data siswa. Silakan hubungi admin.'
            ]);
        }

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
        $baseYears = [$currentYear - 1, $currentYear, $currentYear + 1];
        $yearOptions = collect(array_merge(
            $baseYears,
            Perkembangan::where('siswa_id', $siswa->id)
                ->where('status', 'disetujui')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun')
                ->all()
        ))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();

        $shouldShowReports = $request->filled('bulan') && $request->filled('tahun');

        if ($shouldShowReports) {
            $query = Perkembangan::with(['detailPerkembangans', 'guru'])
                ->where('siswa_id', $siswa->id)
                ->where('kelas_id', $siswa->kelas_id)
                ->where('status', 'disetujui')
                ->where('bulan', $request->integer('bulan'))
                ->where('tahun', $request->integer('tahun'))
                ->latest('tahun')
                ->latest('bulan');

            $perkembangans = $query->paginate(8)->withQueryString();
        } else {
            $perkembangans = new LengthAwarePaginator([], 0, 8, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }

        return view('orang_tua.laporan.index', compact('siswa', 'perkembangans', 'monthOptions', 'yearOptions', 'shouldShowReports'));
    }
}
