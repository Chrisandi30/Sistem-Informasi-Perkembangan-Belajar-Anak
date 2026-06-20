<?php

// Controller: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\Perkembangan;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Susun data yang ditampilkan pada kartu statistik.
    private function statCard(string $label, string|int $value, string $note, string $accent, string $icon): array
    {
        return compact('label', 'value', 'note', 'accent', 'icon');
    }

    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $guardOrder = collect([
            session('active_guard'),
            'admin',
            'guru',
            'kepala_sekolah',
            'orang_tua',
            'web',
        ])->filter()->unique()->values();

        $activeGuard = $guardOrder->first(fn ($guard) => Auth::guard($guard)->check());
        $user = $activeGuard ? Auth::guard($activeGuard)->user() : null;

        if (! $user) {
            return redirect()->route('login');
        }

        session(['active_guard' => $activeGuard]);

        if ($user->role === 'admin') {
            $statistik = Kelas::withCount('siswas')->orderBy('nama_kelas')->get();
            $laporanPerkembanganBulanIni = Perkembangan::where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->count();
            $statusLaporanPerKelas = Kelas::withCount([
                'perkembangans as laporan_bulan_ini_count' => fn ($query) => $query
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year),
            ])->orderBy('nama_kelas')->get();
            $tahunAjaranAktif = TahunAjaran::where('is_active', true)->value('tahun_ajaran') ?? '-';

            $adminStats = [
                $this->statCard('Total Siswa', Siswa::count(), 'Jumlah seluruh siswa', 'accent-purple', 'fa-user-graduate'),
                $this->statCard('Total Guru', Guru::count(), 'Jumlah seluruh guru', 'accent-blue', 'fa-chalkboard-user'),
                $this->statCard('Total Kelas', Kelas::count(), 'Kelas yang terdaftar', 'accent-green', 'fa-school'),
                $this->statCard('Mata Pelajaran', MataPelajaran::count(), 'Mata pelajaran yang terdaftar', 'accent-orange', 'fa-book-open'),
                $this->statCard('Laporan Perkembangan', $laporanPerkembanganBulanIni, 'Total laporan siswa bulan ini', 'accent-red', 'fa-chart-line'),
                $this->statCard('Tahun Ajaran', $tahunAjaranAktif, 'Periode yang berjalan', 'accent-violet', 'fa-calendar-check'),
                $this->statCard('Pengumuman', Pengumuman::count(), 'Pengumuman yang tersedia', 'accent-gray', 'fa-bullhorn'),
            ];

            return view('dashboard', compact('statistik', 'statusLaporanPerKelas', 'adminStats'));
        }

        if ($user->role === 'guru') {
            $guru = $user->guru;
            $jumlahSiswa = Siswa::where('kelas_id', $guru?->kelas_id)->count();
            $jumlahLaporan = Perkembangan::where('guru_id', $guru?->id)
                ->where('kelas_id', $guru?->kelas_id)
                ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                ->count();
            $jumlahMapel = MataPelajaran::where('kelas_id', $guru?->kelas_id)->where('is_active', true)->count();
            $laporanBulanIni = Perkembangan::where('guru_id', $guru?->id)
                ->where('kelas_id', $guru?->kelas_id)
                ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                ->where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->count();
            $siswaSudahDilaporkanBulanIni = Perkembangan::where('guru_id', $guru?->id)
                ->where('kelas_id', $guru?->kelas_id)
                ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                ->where('bulan', now()->month)
                ->where('tahun', now()->year)
                ->distinct('siswa_id')
                ->count('siswa_id');
            $statusLaporanGuruBulanIni = [
                'menunggu' => Perkembangan::where('guru_id', $guru?->id)
                    ->where('kelas_id', $guru?->kelas_id)
                    ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->where('status', 'menunggu')
                    ->count(),
                'revisi' => Perkembangan::where('guru_id', $guru?->id)
                    ->where('kelas_id', $guru?->kelas_id)
                    ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->where('status', 'revisi')
                    ->count(),
                'disetujui' => Perkembangan::where('guru_id', $guru?->id)
                    ->where('kelas_id', $guru?->kelas_id)
                    ->whereHas('siswa', fn ($query) => $query->where('kelas_id', $guru?->kelas_id))
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->where('status', 'disetujui')
                    ->count(),
            ];
            $statusLaporanSiswaBulanIni = [
                [
                    'label' => 'Sudah Dilaporkan',
                    'value' => $siswaSudahDilaporkanBulanIni . ' siswa',
                ],
                [
                    'label' => 'Belum Dilaporkan',
                    'value' => max($jumlahSiswa - $siswaSudahDilaporkanBulanIni, 0) . ' siswa',
                ],
            ];

            $guruStats = [
                $this->statCard('Kelas Diampu', $guru?->kelas?->nama_kelas ?? '-', 'Kelas utama yang Anda tangani', 'accent-purple', 'fa-school'),
                $this->statCard('Jumlah Siswa', $jumlahSiswa, 'Total siswa di kelas Anda', 'accent-blue', 'fa-user-graduate'),
                $this->statCard('Mata Pelajaran', $jumlahMapel, 'Mapel aktif untuk kelas Anda', 'accent-green', 'fa-book-open'),
                $this->statCard('Laporan Bulan Ini', $laporanBulanIni, 'Laporan pada bulan berjalan', 'accent-red', 'fa-calendar-days'),
            ];

            return view('dashboard', compact('jumlahSiswa', 'jumlahLaporan', 'guru', 'guruStats', 'statusLaporanSiswaBulanIni', 'statusLaporanGuruBulanIni'));
        }

        if ($user->role === 'kepala_sekolah') {
            $bulan = now()->month;
            $tahun = now()->year;

            // Hitung seluruh status berdasarkan periode laporan bulan berjalan.
            $laporanPeriodeBerjalan = Perkembangan::query()
                ->where('bulan', $bulan)
                ->where('tahun', $tahun);

            $menungguValidasi = (clone $laporanPeriodeBerjalan)
                ->where('status', 'menunggu')
                ->count();
            $revisi = (clone $laporanPeriodeBerjalan)
                ->where('status', 'revisi')
                ->count();
            $disetujui = (clone $laporanPeriodeBerjalan)
                ->where('status', 'disetujui')
                ->count();

            $statusValidasiPerKelas = Kelas::withCount([
                'perkembangans as menunggu_count' => fn ($q) => $q->where('status', 'menunggu')->where('bulan', $bulan)->where('tahun', $tahun),
                'perkembangans as revisi_count' => fn ($q) => $q->where('status', 'revisi')->where('bulan', $bulan)->where('tahun', $tahun),
                'perkembangans as disetujui_count' => fn ($q) => $q->where('status', 'disetujui')->where('bulan', $bulan)->where('tahun', $tahun),
            ])->orderBy('nama_kelas')->get();

            $kepsekStats = [
                $this->statCard('Menunggu', $menungguValidasi, 'Laporan belum diperiksa', 'accent-orange', 'fa-hourglass-half'),
                $this->statCard('Disetujui', $disetujui, 'Laporan sudah disetujui', 'accent-green', 'fa-circle-check'),
                $this->statCard('Revisi', $revisi, 'Laporan perlu perbaikan', 'accent-red', 'fa-pen-to-square'),
                $this->statCard('Laporan Bulan Ini', (clone $laporanPeriodeBerjalan)->count(), 'Total laporan periode berjalan', 'accent-violet', 'fa-calendar-days'),
            ];

            return view('dashboard', compact('kepsekStats', 'statusValidasiPerKelas'));
        }

        return redirect()->route('orang-tua.profil.index');
    }
}
