<?php

// Controller: app/Http/Controllers/Admin/LaporanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function kelas(Request $request)
    {
        $query = Kelas::with(['siswas.tahunAjaran', 'gurus'])->withCount('siswas')->orderBy('nama_kelas');

        if ($request->filled('kelas_id')) {
            $query->where('id', $request->integer('kelas_id'));
        }

        $kelas = $query->get();
        $allKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admin.laporan.kelas', compact('kelas', 'allKelas'));
    }

    public function perkembangan(Request $request)
    {
        return redirect()->route('admin.perkembangan.index', $request->query());
    }

    public function exportKelasPdf(Request $request)
    {
        if (! $request->filled('kelas_id')) {
            return back()->withErrors(['Pilih kelas terlebih dahulu untuk export PDF.']);
        }

        $kelas = Kelas::with(['siswas.tahunAjaran', 'gurus'])
            ->withCount('siswas')
            ->where('id', $request->integer('kelas_id'))
            ->orderBy('nama_kelas')
            ->get();

        if ($kelas->isEmpty()) {
            return back()->withErrors(['Data kelas tidak ditemukan untuk diexport.']);
        }

        $pdf = Pdf::loadView('admin.laporan.pdf-kelas', [
            'kelas' => $kelas,
            'printedAt' => now(),
        ])->setPaper('a4', 'portrait');

        $name = 'Laporan Kelas ' . $kelas->first()->nama_kelas . '.pdf';

        return $pdf->download($name);
    }

    public function printKelas(Request $request)
    {
        if (! $request->filled('kelas_id')) {
            return back()->withErrors(['Pilih kelas terlebih dahulu untuk dicetak.']);
        }

        $kelas = Kelas::with(['siswas.tahunAjaran', 'gurus'])
            ->withCount('siswas')
            ->where('id', $request->integer('kelas_id'))
            ->orderBy('nama_kelas')
            ->get();

        if ($kelas->isEmpty()) {
            return back()->withErrors(['Data kelas tidak ditemukan untuk dicetak.']);
        }

        return view('admin.laporan.print-kelas', [
            'kelas' => $kelas,
            'printedAt' => now(),
        ]);
    }
}