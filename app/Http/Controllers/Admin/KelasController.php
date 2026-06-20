<?php

// Controller: app/Http/Controllers/Admin/KelasController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index()
    {
        $kelas = Kelas::latest()->paginate(10);
        return view('admin.kelas.index', compact('kelas'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        return view('admin.kelas.create');
    }

    // Validasi dan simpan data baru ke database.
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:20', 'unique:kelas,nama_kelas'],
        ]);

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(Kelas $kela)
    {
        return view('admin.kelas.edit', ['kelas' => $kela]);
    }

    // Validasi dan simpan perubahan data.
    public function update(Request $request, Kelas $kela)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:20', 'unique:kelas,nama_kelas,' . $kela->id],
        ]);

        $kela->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diubah.');
    }

    // Hapus data yang dipilih dari database.
    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
