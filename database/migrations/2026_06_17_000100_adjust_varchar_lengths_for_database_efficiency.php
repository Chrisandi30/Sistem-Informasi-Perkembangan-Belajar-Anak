<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = [
        ['users', 'name', 40, false],
        ['gurus', 'nama', 40, false],
        ['gurus', 'jenjang_pendidikan', 2, false],
        ['siswas', 'nama', 40, false],
        ['siswas', 'nis', 11, true],
        ['siswas', 'nisn', 10, true],
        ['siswas', 'tempat_lahir', 20, true],
        ['siswas', 'nama_ayah', 40, true],
        ['siswas', 'nama_ibu', 40, true],
        ['siswas', 'nama_wali', 40, true],
        ['siswas', 'nomor_telepon', 13, true],
        ['mata_pelajarans', 'nama_mapel', 40, false],
        ['detail_perkembangans', 'kategori_aspek', 50, false],
        ['detail_perkembangans', 'nama_aspek', 20, false],
        ['perkembangan_non_akademis', 'kategori_aspek', 50, false],
        ['perkembangan_non_akademis', 'nama_aspek', 20, false],
        ['pengumuman', 'judul', 30, false],
        ['tahun_ajaran', 'tahun_ajaran', 10, false],
    ];

    public function up(): void
    {
        $this->normalizeExistingData();

        foreach ($this->columns as [$table, $column, $length, $nullable]) {
            $this->modifyString($table, $column, $length, $nullable);
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $definition) {
            [$table, $column, , $nullable] = $definition;
            $this->modifyString($table, $column, 255, $nullable);
        }
    }

    private function modifyString(string $table, string $column, int $length, bool $nullable): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        $nullSql = $nullable ? 'NULL' : 'NOT NULL';

        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR({$length}) {$nullSql}");
    }

    private function normalizeExistingData(): void
    {
        if (Schema::hasTable('gurus') && Schema::hasColumn('gurus', 'jenjang_pendidikan')) {
            DB::table('gurus')
                ->where('jenjang_pendidikan', 'like', '%S1%')
                ->update(['jenjang_pendidikan' => 'S1']);

            DB::table('gurus')
                ->where('jenjang_pendidikan', 'like', '%S2%')
                ->update(['jenjang_pendidikan' => 'S2']);
        }

        if (Schema::hasTable('siswas')) {
            if (Schema::hasColumn('siswas', 'nis')) {
                DB::statement("UPDATE `siswas` SET `nis` = LEFT(REPLACE(REPLACE(REPLACE(`nis`, '.', ''), '-', ''), ' ', ''), 8) WHERE `nis` IS NOT NULL");
            }

            if (Schema::hasColumn('siswas', 'nisn')) {
                DB::statement("UPDATE `siswas` SET `nisn` = LEFT(REPLACE(REPLACE(REPLACE(`nisn`, '.', ''), '-', ''), ' ', ''), 10) WHERE `nisn` IS NOT NULL");
            }
        }
    }
};
