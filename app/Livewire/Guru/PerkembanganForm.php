<?php

// Livewire: app/Livewire/Guru/PerkembanganForm.php

namespace App\Livewire\Guru;

use App\Livewire\Concerns\ReturnsToIndex;
use App\Models\MataPelajaran;
use App\Models\Perkembangan;
use App\Models\PerkembanganNonAkademis;
use App\Models\Siswa;
use App\Services\WhatsAppService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PerkembanganForm extends Component
{
    use ReturnsToIndex;

    public ?Perkembangan $perkembangan = null;
    public string $siswa_id = '';
    public string $bulan = '';
    public string $tahun = '';
    public array $detail_aspek = [];
    public string $catatan_pengembangan = '';
    public string $guru_id = '';

    private WhatsAppService $whatsAppService;

    // Siapkan service yang dibutuhkan oleh komponen.
    public function boot(WhatsAppService $whatsAppService): void
    {
        $this->whatsAppService = $whatsAppService;
    }

    // Isi kondisi awal saat halaman atau komponen dibuka.
    public function mount(?Perkembangan $perkembangan = null): void
    {
        $guru = auth()->user()?->guru;
        abort_unless($guru, 403);

        $this->guru_id = (string) $guru->id;
        $this->perkembangan = $perkembangan;
        $this->initializeReturnTo(
            $this->isEditing()
                ? route('guru.perkembangan.index')
                : route('guru.siswa.index')
        );
        $this->bulan = (string) now()->month;
        $this->tahun = (string) now()->year;

        if ($this->isEditing()) {
            abort_if($perkembangan->guru_id !== $guru->id, 403);
            $perkembangan->loadMissing(['detailPerkembangans', 'siswa']);

            $this->siswa_id = (string) $perkembangan->siswa_id;
            $this->bulan = (string) $perkembangan->bulan;
            $this->tahun = (string) $perkembangan->tahun;
            $this->catatan_pengembangan = $perkembangan->catatan_pengembangan ?? '';
        } else {
            $requestedSiswaId = (string) request()->query('siswa', '');
            $allowedSiswaIds = $this->siswas()->pluck('id')->map(fn ($id) => (string) $id);

            if ($requestedSiswaId !== '' && $allowedSiswaIds->contains($requestedSiswaId)) {
                $this->siswa_id = $requestedSiswaId;
            } else {
                $this->siswa_id = (string) optional($this->siswas()->first())->id;
            }
        }

        $this->syncDetailAspek();
    }

    // Sinkronkan data setelah nilai formulir berubah.
    public function updatedSiswaId(): void
    {
        $this->syncDetailAspek();
    }

    // Validasi lalu simpan data dari formulir.
    public function save()
    {
        $wasEditing = $this->isEditing();
        $guru = $this->currentGuru();

        $data = $this->validate($this->rules());

        $siswa = Siswa::findOrFail($data['siswa_id']);
        abort_if($siswa->kelas_id !== $guru->kelas_id, 403);

        $mapelsKelas = $this->mapels();
        $this->validateAllSubjectsFilled($data['detail_aspek'], $mapelsKelas->pluck('id'));

        if (! $this->validateAnyDevelopmentFieldFilled($data['detail_aspek'])) {
            return;
        }

        $duplicateExists = Perkembangan::query()
            ->where('siswa_id', $data['siswa_id'])
            ->where('bulan', (int) $data['bulan'])
            ->where('tahun', (int) $data['tahun'])
            ->when($this->perkembangan?->exists, fn ($query) => $query->whereKeyNot($this->perkembangan->id))
            ->exists();

        if ($duplicateExists) {
            $message = 'Laporan bulanan siswa pada bulan dan tahun tersebut sudah ada.';
            $this->addError('siswa_id', $message);
            $this->dispatch(
                'app-feedback',
                type: 'error',
                title: 'Laporan Sudah Ada',
                message: $message,
                timer: 2200,
                showConfirmButton: false,
                timerProgressBar: true,
                confirmButtonColor: '#dc2626',
            );

            return;
        }

        DB::transaction(function () use ($data, $siswa, $guru, $wasEditing) {
            $payload = [
                'siswa_id' => $siswa->id,
                'guru_id' => $guru->id,
                'kelas_id' => $siswa->kelas_id,
                'bulan' => (int) $data['bulan'],
                'tahun' => (int) $data['tahun'],
                'catatan_pengembangan' => $data['catatan_pengembangan'] ?: null,
            ];

            if ($wasEditing) {
                $perkembangan = $this->perkembangan;
                $perkembangan->update($payload);
                $perkembangan->detailPerkembangans()->delete();
            } else {
                $perkembangan = Perkembangan::create($payload);
                $this->perkembangan = $perkembangan;
            }

            foreach ($data['detail_aspek'] as $index => $item) {
                $perkembangan->detailPerkembangans()->create([
                    'mata_pelajaran_id' => $item['mata_pelajaran_id'] ?? null,
                    'perkembangan_non_akademis_id' => $item['perkembangan_non_akademis_id'] ?? null,
                    'kategori_aspek' => $item['kategori_aspek'],
                    'nama_aspek' => $item['nama_aspek'],
                    'hal_berkembang' => $this->optionalText($item['hal_berkembang'] ?? null),
                    'perlu_diperhatikan' => $this->optionalText($item['perlu_diperhatikan'] ?? null),
                    'urutan' => $index + 1,
                ]);
            }
        });

        if (! $wasEditing) {
            $message = "Laporan perkembangan {$siswa->nama} bulan {$data['bulan']}/{$data['tahun']} sudah tersedia di sistem.";
            $this->whatsAppService->sendWebsiteLink($siswa, $message);
            session()->flash('success', 'Laporan bulanan berhasil ditambahkan.');
        } else {
            session()->flash('success', 'Laporan bulanan berhasil diperbarui.');
        }

        // Setelah simpan, kembali ke halaman daftar asal beserta posisi pagination-nya.
        return $this->redirectToIndex();
    }

    // Ambil data guru yang sedang login.
    private function currentGuru()
    {
        return auth()->user()?->guru;
    }

    // Tentukan aturan validasi data formulir.
    private function rules(): array
    {
        return [
            'siswa_id' => ['required', 'exists:siswas,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2100'],
            'detail_aspek' => ['required', 'array', 'min:1'],
            'detail_aspek.*.kategori_aspek' => ['required', 'string'],
            'detail_aspek.*.nama_aspek' => ['required', 'string'],
            'detail_aspek.*.mata_pelajaran_id' => ['nullable', 'integer', 'exists:mata_pelajarans,id'],
            'detail_aspek.*.perkembangan_non_akademis_id' => ['nullable', 'integer', 'exists:perkembangan_non_akademis,id'],
            'detail_aspek.*.hal_berkembang' => ['nullable', 'string'],
            'detail_aspek.*.perlu_diperhatikan' => ['nullable', 'string'],
            'catatan_pengembangan' => ['nullable', 'string'],
        ];
    }

    // Tangani proses mapels.
    private function mapels(): Collection
    {
        $guru = $this->currentGuru();
        abort_unless($guru, 403);
        return MataPelajaran::where('kelas_id', $guru->kelas_id)
            ->where('is_active', true)
            ->orderBy('nama_mapel')
            ->get(['id', 'nama_mapel']);
    }

    // Tangani proses siswas.
    private function siswas(): Collection
    {
        $guru = $this->currentGuru();
        abort_unless($guru, 403);
        return Siswa::where('kelas_id', $guru->kelas_id)->orderBy('nama')->get(['id', 'nama']);
    }

    // Tangani proses non academic aspects.
    private function nonAcademicAspects(): Collection
    {
        return PerkembanganNonAkademis::query()
            ->where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('kategori_aspek')
            ->orderBy('nama_aspek')
            ->get(['id', 'kategori_aspek', 'nama_aspek', 'urutan']);
    }

    // Tangani proses selected siswa.
    private function selectedSiswa(): ?Siswa
    {
        if ($this->siswa_id === '') {
            return null;
        }

        return $this->siswas()->firstWhere('id', (int) $this->siswa_id);
    }

    // Sinkronkan rincian aspek perkembangan pada formulir.
    private function syncDetailAspek(): void
    {
        $current = collect($this->detail_aspek)
            ->mapWithKeys(fn ($item) => [$this->detailKey($item['kategori_aspek'] ?? '', $item['nama_aspek'] ?? '') => $item]);

        $items = collect();
        $mapelLookup = $this->mapels()->mapWithKeys(fn ($mapel) => [mb_strtolower((string) $mapel->nama_mapel) => (int) $mapel->id]);
        $nonAcademicLookup = $this->nonAcademicAspects()
            ->mapWithKeys(fn ($aspek) => [$this->detailKey($aspek->kategori_aspek, $aspek->nama_aspek) => (int) $aspek->id]);

        if ($this->isEditing() && $this->perkembangan->detailPerkembangans->isNotEmpty()) {
            $items = $this->perkembangan->detailPerkembangans->map(function ($detail) use ($current, $mapelLookup, $nonAcademicLookup) {
                $key = $this->detailKey($detail->kategori_aspek, $detail->nama_aspek);
                $currentItem = $current->get($key, []);

                return [
                    'kategori_aspek' => $detail->kategori_aspek,
                    'nama_aspek' => $detail->nama_aspek,
                    'mata_pelajaran_id' => (int) ($detail->mata_pelajaran_id ?? 0) ?: $mapelLookup->get(mb_strtolower((string) $detail->nama_aspek)),
                    'perkembangan_non_akademis_id' => (int) ($detail->perkembangan_non_akademis_id ?? 0) ?: $nonAcademicLookup->get($key),
                    'hal_berkembang' => $currentItem['hal_berkembang'] ?? ($detail->hal_berkembang ?? ''),
                    'perlu_diperhatikan' => $currentItem['perlu_diperhatikan'] ?? ($detail->perlu_diperhatikan ?? ''),
                ];
            });
        } else {
            foreach ($this->mapels()->values() as $mapel) {
                $key = $this->detailKey('Aspek Akademis', $mapel->nama_mapel);
                $currentItem = $current->get($key, []);

                $items->push([
                    'kategori_aspek' => 'Aspek Akademis',
                    'nama_aspek' => $mapel->nama_mapel,
                    'mata_pelajaran_id' => (int) $mapel->id,
                    'perkembangan_non_akademis_id' => null,
                    'hal_berkembang' => $currentItem['hal_berkembang'] ?? '',
                    'perlu_diperhatikan' => $currentItem['perlu_diperhatikan'] ?? '',
                ]);
            }

            foreach ($this->nonAcademicAspects() as $aspek) {
                $key = $this->detailKey($aspek->kategori_aspek, $aspek->nama_aspek);
                $currentItem = $current->get($key, []);

                $items->push([
                    'kategori_aspek' => $aspek->kategori_aspek,
                    'nama_aspek' => $aspek->nama_aspek,
                    'mata_pelajaran_id' => null,
                    'perkembangan_non_akademis_id' => (int) $aspek->id,
                    'hal_berkembang' => $currentItem['hal_berkembang'] ?? '',
                    'perlu_diperhatikan' => $currentItem['perlu_diperhatikan'] ?? '',
                ]);
            }
        }

        $this->detail_aspek = $items->values()->all();
    }

    // Pastikan seluruh mata pelajaran telah tersedia.
    private function validateAllSubjectsFilled(array $detailAspek, Collection $kelasIds): void
    {
        $sentIds = collect($detailAspek)
            ->pluck('mata_pelajaran_id')
            ->filter()
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->sort()
            ->values();
        $kelasIds = $kelasIds->map(fn ($v) => (int) $v)->sort()->values();
        abort_if($sentIds->count() !== $kelasIds->count(), 422, 'Semua mata pelajaran kelas harus diisi.');
        abort_if($sentIds->diff($kelasIds)->isNotEmpty(), 422, 'Daftar mata pelajaran tidak sesuai dengan data admin.');
    }

    // Pastikan minimal satu catatan perkembangan diisi.
    private function validateAnyDevelopmentFieldFilled(array $detailAspek): bool
    {
        $isValid = true;

        foreach ($detailAspek as $index => $item) {
            $halBerkembang = $this->optionalText($item['hal_berkembang'] ?? null);
            $perluDiperhatikan = $this->optionalText($item['perlu_diperhatikan'] ?? null);

            if ($halBerkembang === null && $perluDiperhatikan === null) {
                $message = 'Isi minimal salah satu bagian pada aspek ini.';
                $this->addError("detail_aspek.$index.hal_berkembang", $message);
                $this->addError("detail_aspek.$index.perlu_diperhatikan", $message);
                $isValid = false;
            }
        }

        return $isValid;
    }

    // Ubah teks kosong menjadi nilai null.
    private function optionalText(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    // Buat kunci unik untuk rincian aspek perkembangan.
    private function detailKey(string $kategori, string $nama): string
    {
        return $kategori.'::'.$nama;
    }

    // Sediakan daftar nama bulan untuk filter.
    private function monthOptions(): array
    {
        return [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
    }

    // Sediakan daftar tahun untuk filter.
    private function yearOptions(array $years = []): array
    {
        $currentYear = now()->year;
        $baseYears = [$currentYear - 1, $currentYear, $currentYear + 1];

        return collect(array_merge($baseYears, $years))
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();
    }

    // Periksa apakah formulir sedang dalam mode edit.
    private function isEditing(): bool
    {
        return $this->perkembangan instanceof Perkembangan && $this->perkembangan->exists;
    }

    // Kirim data komponen ke tampilan Livewire.
    public function render()
    {
        return view('livewire.guru.perkembangan-form', [
            'isEdit' => $this->isEditing(),
            'selectedSiswa' => $this->selectedSiswa(),
            'monthOptions' => $this->monthOptions(),
            'yearOptions' => $this->yearOptions($this->isEditing() ? [(int) $this->tahun] : []),
            'groupedDetailAspek' => collect($this->detail_aspek)
                ->values()
                ->map(fn ($item, $index) => ['index' => $index] + $item)
                ->groupBy('kategori_aspek'),
        ]);
    }
}
