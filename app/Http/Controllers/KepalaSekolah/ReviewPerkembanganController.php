<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Perkembangan;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewPerkembanganController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    public function index(Request $request)
    {
        return view('kepala_sekolah.perkembangan.index');
    }

    public function show(Perkembangan $perkembangan)
    {
        $perkembangan->load(['siswa.kelas', 'siswa.tahunAjaran', 'guru.kelas', 'detailPerkembangans', 'validator']);

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

        return view('kepala_sekolah.perkembangan.show', compact('perkembangan', 'monthOptions'));
    }

    public function approve(Request $request, Perkembangan $perkembangan)
    {
        abort_if($perkembangan->status === 'disetujui', 422, 'Laporan ini sudah disetujui.');

        DB::transaction(function () use ($perkembangan) {
            $perkembangan->update([
                'status' => 'disetujui',
                'validated_by' => auth()->id(),
                'validated_at' => now(),
                'catatan_validasi' => null,
            ]);
        });

        $siswa = $perkembangan->siswa;
        if ($siswa) {
            $message = "Laporan perkembangan {$siswa->nama} bulan {$perkembangan->bulan}/{$perkembangan->tahun} sudah disetujui dan tersedia di sistem.";
            $this->whatsAppService->sendWebsiteLink($siswa, $message);
        }

        return redirect()->route('kepala-sekolah.review.index')->with('success', 'Laporan berhasil disetujui.');
    }

    public function reject(Request $request, Perkembangan $perkembangan)
    {
        $data = $request->validate([
            'catatan_validasi' => ['required', 'string', 'max:500'],
        ], [
            'catatan_validasi.required' => 'Catatan penolakan wajib diisi.',
        ]);

        DB::transaction(function () use ($perkembangan, $data) {
            $perkembangan->update([
                'status' => 'revisi',
                'validated_by' => auth()->id(),
                'validated_at' => now(),
                'catatan_validasi' => $data['catatan_validasi'],
            ]);
        });

        return redirect()->route('kepala-sekolah.review.index')->with('success', 'Revisi berhasil dikirim. Catatan sudah tersimpan.');
    }
}
