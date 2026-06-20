<?php

// Controller: app/Http/Controllers/Admin/PengumumanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $pengumuman = Pengumuman::latest('tanggal_terbit')->paginate(10);
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    // Validasi dan simpan data baru ke database.
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:30'],
            'isi' => ['required', 'string'],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_berakhir' => ['required', 'date', 'after_or_equal:tanggal_terbit'],
        ]);

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    // Validasi dan simpan perubahan data.
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:30'],
            'isi' => ['required', 'string'],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_berakhir' => ['required', 'date', 'after_or_equal:tanggal_terbit'],
        ]);

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diubah.');
    }

    // Hapus data yang dipilih dari database.
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
