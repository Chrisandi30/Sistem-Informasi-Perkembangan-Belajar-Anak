<?php

// Controller: app/Http/Controllers/Admin/MataPelajaranController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mataPelajarans = MataPelajaran::with('kelas')->latest()->paginate(10);
        return view('admin.mata_pelajaran.index', compact('mataPelajarans'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.mata_pelajaran.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:40'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        MataPelajaran::create($data);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mata_pelajaran)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.mata_pelajaran.edit', ['mataPelajaran' => $mata_pelajaran, 'kelas' => $kelas]);
    }

    public function update(Request $request, MataPelajaran $mata_pelajaran)
    {
        $data = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:40'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $mata_pelajaran->update($data);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diubah.');
    }

    public function destroy(MataPelajaran $mata_pelajaran)
    {
        $mata_pelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
