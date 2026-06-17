<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('perkembangan_non_akademis')->exists()) {
            return;
        }

        $now = now();

        DB::table('perkembangan_non_akademis')->insert([
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Keterampilan Sosial',
                'urutan' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Sikap dan Perilaku',
                'urutan' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aspek' => 'Aspek Perilaku dan Sosial Emosional',
                'nama_aspek' => 'Pengontrolan Emosi',
                'urutan' => 3,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aspek' => 'Aspek Pertumbuhan dan Perkembangan',
                'nama_aspek' => 'Kemandirian Anak',
                'urutan' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'kategori_aspek' => 'Aspek Pertumbuhan dan Perkembangan',
                'nama_aspek' => 'Kematangan Anak',
                'urutan' => 5,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('perkembangan_non_akademis')
            ->whereIn('nama_aspek', [
                'Keterampilan Sosial',
                'Sikap dan Perilaku',
                'Pengontrolan Emosi',
                'Kemandirian Anak',
                'Kematangan Anak',
            ])
            ->delete();
    }
};
