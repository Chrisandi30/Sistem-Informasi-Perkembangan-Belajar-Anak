<?php
namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $today = now()->toDateString();

        $pengumuman = Pengumuman::whereDate('tanggal_terbit', '<=', $today)
            ->whereDate('tanggal_berakhir', '>=', $today)
            ->latest('tanggal_terbit')
            ->paginate(10);

        return view('orang_tua.pengumuman.index', compact('pengumuman'));
    }
}
