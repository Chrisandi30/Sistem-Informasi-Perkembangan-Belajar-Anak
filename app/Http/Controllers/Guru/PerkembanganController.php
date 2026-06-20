<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Perkembangan;
use App\Models\Siswa;
use App\Support\SafeReturnUrl;
use Illuminate\Http\Request;

class PerkembanganController extends Controller
{
    // Tampilkan daftar data pada halaman utama.
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        abort_unless($guru, 403);

        $query = Perkembangan::with(['siswa.tahunAjaran'])
            ->where('guru_id', $guru->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->integer('siswa_id'));
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->integer('bulan'));
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->integer('tahun'));
        }

        $perkembangans = $query->paginate(10)->withQueryString();
        $siswas = Siswa::where('kelas_id', $guru->kelas_id)->orderBy('nama')->get();
        $monthOptions = $this->monthOptions();
        $yearOptions = $this->filterYearOptions(
            Perkembangan::where('guru_id', $guru->id)
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun')
                ->all()
        );

        return view('guru.perkembangan.index', compact('perkembangans', 'siswas', 'monthOptions', 'yearOptions'));
    }

    // Tampilkan detail data yang dipilih.
    public function show(Request $request, Perkembangan $perkembangan)
    {
        $guru = auth()->user()->guru;
        abort_if(
            $perkembangan->guru_id !== $guru?->id
            || $perkembangan->kelas_id !== $guru?->kelas_id
            || $perkembangan->siswa?->kelas_id !== $guru?->kelas_id,
            403
        );

        $perkembangan->load(['siswa.kelas', 'siswa.tahunAjaran', 'guru', 'detailPerkembangans', 'validator']);
        $monthOptions = $this->monthOptions();
        $returnTo = SafeReturnUrl::fromRequest($request, route('guru.perkembangan.index'));

        return view('guru.perkembangan.show', compact('perkembangan', 'monthOptions', 'returnTo'));
    }

    // Tampilkan halaman untuk menambahkan data.
    public function create()
    {
        $guru = auth()->user()->guru;
        abort_unless($guru, 403);

        return view('guru.perkembangan.create');
    }

    // Tampilkan halaman untuk mengubah data.
    public function edit(Perkembangan $perkembangan)
    {
        $guru = auth()->user()->guru;
        abort_if(
            $perkembangan->guru_id !== $guru?->id
            || $perkembangan->kelas_id !== $guru?->kelas_id
            || $perkembangan->siswa?->kelas_id !== $guru?->kelas_id,
            403
        );

        return view('guru.perkembangan.edit', compact('perkembangan'));
    }

    // Hapus data yang dipilih dari database.
    public function destroy(Perkembangan $perkembangan)
    {
        $guru = auth()->user()->guru;
        abort_if(
            $perkembangan->guru_id !== $guru?->id
            || $perkembangan->kelas_id !== $guru?->kelas_id
            || $perkembangan->siswa?->kelas_id !== $guru?->kelas_id,
            403
        );

        $perkembangan->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }

    // Sediakan daftar nama bulan untuk filter.
    private function monthOptions(): array
    {
        return [
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
    }

    // Susun daftar tahun yang dapat dipilih.
    private function filterYearOptions(array $years = []): array
    {
        $currentYear = now()->year;
        $baseYears = [$currentYear - 1, $currentYear, $currentYear + 1];

        return collect(array_merge($baseYears, $years))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();
    }
}
