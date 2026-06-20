<?php
namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Perkembangan;
use App\Services\WhatsAppService;
use App\Support\SafeReturnUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewPerkembanganController extends Controller
{
    // Siapkan dependency yang digunakan oleh class.
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    // Tampilkan daftar laporan yang perlu ditinjau kepala sekolah.
    public function index(Request $request)
    {
        return view('kepala_sekolah.perkembangan.index');
    }

    // Ambil seluruh relasi yang dibutuhkan pada halaman detail laporan.
    public function show(Request $request, Perkembangan $perkembangan)
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

        $returnTo = SafeReturnUrl::fromRequest($request, route('kepala-sekolah.review.index'));

        return view('kepala_sekolah.perkembangan.show', compact('perkembangan', 'monthOptions', 'returnTo'));
    }

    // Setujui laporan perkembangan yang telah diperiksa.
    public function approve(Request $request, Perkembangan $perkembangan)
    {
        abort_if($perkembangan->status === 'disetujui', 422, 'Laporan ini sudah disetujui.');

        // Simpan status persetujuan dan data validator secara atomik.
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

        $returnTo = SafeReturnUrl::fromRequest($request, route('kepala-sekolah.review.index'));

        return redirect()->to($returnTo)->with('success', 'Laporan berhasil disetujui.');
    }

    // Kembalikan laporan kepada guru untuk direvisi.
    public function reject(Request $request, Perkembangan $perkembangan)
    {
        // Pastikan alasan revisi diisi sebelum laporan dikembalikan ke guru.
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

        $returnTo = SafeReturnUrl::fromRequest($request, route('kepala-sekolah.review.index'));

        return redirect()->to($returnTo)->with('success', 'Revisi berhasil dikirim. Catatan sudah tersimpan.');
    }
}
