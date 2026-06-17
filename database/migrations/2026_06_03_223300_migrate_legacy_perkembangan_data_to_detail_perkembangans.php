<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('detail_perkembangans') || ! Schema::hasTable('perkembangans')) {
            return;
        }

        $now = now();
        $nonAcademicLookup = DB::table('perkembangan_non_akademis')
            ->get(['id', 'kategori_aspek', 'nama_aspek', 'urutan'])
            ->keyBy(fn ($item) => $item->kategori_aspek.'::'.$item->nama_aspek);

        $mapelLookup = DB::table('mata_pelajarans')
            ->get(['id', 'kelas_id', 'nama_mapel'])
            ->keyBy(fn ($item) => $item->kelas_id.'::'.mb_strtolower((string) $item->nama_mapel));

        DB::table('detail_perkembangans')
            ->orderBy('id')
            ->chunkById(200, function ($details) use ($nonAcademicLookup, $mapelLookup) {
                foreach ($details as $detail) {
                    $updates = [];

                    if ($detail->kategori_aspek === 'Aspek Akademis' && empty($detail->mata_pelajaran_id)) {
                        $perkembangan = DB::table('perkembangans')->where('id', $detail->perkembangan_id)->first(['kelas_id']);
                        $mapelId = $perkembangan
                            ? data_get($mapelLookup->get($perkembangan->kelas_id.'::'.mb_strtolower((string) $detail->nama_aspek)), 'id')
                            : null;

                        if ($mapelId) {
                            $updates['mata_pelajaran_id'] = $mapelId;
                        }
                    }

                    if ($detail->kategori_aspek !== 'Aspek Akademis' && empty($detail->perkembangan_non_akademis_id)) {
                        $master = $nonAcademicLookup->get($detail->kategori_aspek.'::'.$detail->nama_aspek);

                        if ($master) {
                            $updates['perkembangan_non_akademis_id'] = $master->id;
                        }
                    }

                    if ($updates !== []) {
                        DB::table('detail_perkembangans')->where('id', $detail->id)->update($updates);
                    }
                }
            });

        $existingAcademicKeys = DB::table('detail_perkembangans')
            ->whereNotNull('mata_pelajaran_id')
            ->get(['perkembangan_id', 'mata_pelajaran_id'])
            ->mapWithKeys(fn ($item) => [$item->perkembangan_id.'::'.$item->mata_pelajaran_id => true]);

        $legacyAcademics = DB::table('perkembangan_akademis as pa')
            ->join('mata_pelajarans as mp', 'mp.id', '=', 'pa.mata_pelajaran_id')
            ->orderBy('pa.perkembangan_id')
            ->orderBy('mp.nama_mapel')
            ->get([
                'pa.perkembangan_id',
                'pa.mata_pelajaran_id',
                'mp.nama_mapel',
                'pa.deskripsi',
                'pa.deskripsi_sudah',
                'pa.deskripsi_belum',
            ]);

        foreach ($legacyAcademics as $legacyAcademic) {
            $key = $legacyAcademic->perkembangan_id.'::'.$legacyAcademic->mata_pelajaran_id;

            if ($existingAcademicKeys->has($key)) {
                continue;
            }

            DB::table('detail_perkembangans')->insert([
                'perkembangan_id' => $legacyAcademic->perkembangan_id,
                'mata_pelajaran_id' => $legacyAcademic->mata_pelajaran_id,
                'perkembangan_non_akademis_id' => null,
                'kategori_aspek' => 'Aspek Akademis',
                'nama_aspek' => $legacyAcademic->nama_mapel,
                'hal_berkembang' => $legacyAcademic->deskripsi_sudah ?: ($legacyAcademic->deskripsi ?? ''),
                'perlu_diperhatikan' => $legacyAcademic->deskripsi_belum ?? '',
                'urutan' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $existingAcademicKeys->put($key, true);
        }

        $existingNonAcademicKeys = DB::table('detail_perkembangans')
            ->where('kategori_aspek', '!=', 'Aspek Akademis')
            ->get(['perkembangan_id', 'kategori_aspek', 'nama_aspek'])
            ->mapWithKeys(fn ($item) => [$item->perkembangan_id.'::'.$item->kategori_aspek.'::'.$item->nama_aspek => true]);

        $fieldMap = [
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Keterampilan Sosial',
                'sudah' => 'keterampilan_sosial_sudah',
                'belum' => 'keterampilan_sosial_belum',
            ],
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Sikap dan Perilaku',
                'sudah' => 'sikap_perilaku_sudah',
                'belum' => 'sikap_perilaku_belum',
            ],
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Pengontrolan Emosi',
                'sudah' => 'pengontrolan_emosi_sudah',
                'belum' => 'pengontrolan_emosi_belum',
            ],
            [
                'kategori_aspek' => 'Aspek Pertumbuhan dan Perkembangan',
                'nama_aspek' => 'Kemandirian Anak',
                'sudah' => 'kemandirian_sudah',
                'belum' => 'kemandirian_belum',
            ],
            [
                'kategori_aspek' => 'Aspek Pertumbuhan dan Perkembangan',
                'nama_aspek' => 'Kematangan Anak',
                'sudah' => 'kematangan_sudah',
                'belum' => 'kematangan_belum',
            ],
        ];

        DB::table('perkembangans')
            ->orderBy('id')
            ->chunkById(100, function ($perkembangans) use ($fieldMap, $nonAcademicLookup, $existingNonAcademicKeys, $now) {
                foreach ($perkembangans as $perkembangan) {
                    foreach ($fieldMap as $index => $mapping) {
                        $key = $perkembangan->id.'::'.$mapping['kategori_aspek'].'::'.$mapping['nama_aspek'];

                        if ($existingNonAcademicKeys->has($key)) {
                            continue;
                        }

                        $halBerkembang = trim((string) ($perkembangan->{$mapping['sudah']} ?? ''));
                        $perluDiperhatikan = trim((string) ($perkembangan->{$mapping['belum']} ?? ''));

                        if ($halBerkembang === '' && $perluDiperhatikan === '') {
                            continue;
                        }

                        $master = $nonAcademicLookup->get($mapping['kategori_aspek'].'::'.$mapping['nama_aspek']);

                        DB::table('detail_perkembangans')->insert([
                            'perkembangan_id' => $perkembangan->id,
                            'mata_pelajaran_id' => null,
                            'perkembangan_non_akademis_id' => $master?->id,
                            'kategori_aspek' => $mapping['kategori_aspek'],
                            'nama_aspek' => $mapping['nama_aspek'],
                            'hal_berkembang' => $halBerkembang,
                            'perlu_diperhatikan' => $perluDiperhatikan,
                            'urutan' => 100 + $index,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);

                        $existingNonAcademicKeys->put($key, true);
                    }
                }
            });

        DB::table('perkembangans')
            ->orderBy('id')
            ->chunkById(100, function ($perkembangans) {
                foreach ($perkembangans as $perkembangan) {
                    $details = DB::table('detail_perkembangans')
                        ->where('perkembangan_id', $perkembangan->id)
                        ->orderByRaw("CASE WHEN kategori_aspek = 'Aspek Akademis' THEN 0 ELSE 1 END")
                        ->orderBy('urutan')
                        ->orderBy('nama_aspek')
                        ->orderBy('id')
                        ->get(['id']);

                    foreach ($details as $index => $detail) {
                        DB::table('detail_perkembangans')
                            ->where('id', $detail->id)
                            ->update(['urutan' => $index + 1]);
                    }
                }
            });
    }

    public function down(): void
    {
    }
};
