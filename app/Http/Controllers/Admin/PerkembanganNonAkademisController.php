<?php

// Controller: app/Http/Controllers/Admin/PerkembanganNonAkademisController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerkembanganNonAkademis;

class PerkembanganNonAkademisController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        return view('admin.perkembangan_non_akademis.index');
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        return view('admin.perkembangan_non_akademis.create');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(PerkembanganNonAkademis $perkembangan_non_akademi)
    {
        return view('admin.perkembangan_non_akademis.edit', [
            'perkembanganNonAkademis' => $perkembangan_non_akademi,
        ]);
    }
}
