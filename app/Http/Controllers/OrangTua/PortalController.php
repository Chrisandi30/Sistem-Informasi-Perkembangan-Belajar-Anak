<?php

// Controller: app/Http/Controllers/OrangTua/PortalController.php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\Perkembangan;

class PortalController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if ($siswa) {
            $siswa->loadMissing(['kelas', 'tahunAjaran']);
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

        $jumlahLaporan = $siswa ? $siswa->perkembangans()->where('status', 'disetujui')->count() : 0;
        $laporanTerbaru = $siswa
            ? Perkembangan::with('guru')
                ->where('siswa_id', $siswa->id)
                ->where('status', 'disetujui')
                ->latest('tahun')
                ->latest('bulan')
                ->first()
            : null;
        $today = now()->toDateString();
        $pengumumanTerbaru = Pengumuman::whereDate('tanggal_terbit', '<=', $today)
            ->whereDate('tanggal_berakhir', '>=', $today)
            ->latest('tanggal_terbit')
            ->take(3)
            ->get();
        $jumlahPengumuman = Pengumuman::count();

        return view('orang_tua.profil.index', compact(
            'siswa',
            'jumlahLaporan',
            'laporanTerbaru',
            'pengumumanTerbaru',
            'jumlahPengumuman',
            'monthOptions'
        ));
    }
}
