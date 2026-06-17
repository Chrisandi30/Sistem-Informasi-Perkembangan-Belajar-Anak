<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->modifyString('users', 'username', 30);
        $this->modifyString('users', 'name', 40);
        $this->modifyString('users', 'role', 20);
        $this->modifyString('users', 'password', 150);

        $this->modifyString('kelas', 'nama_kelas', 20);

        $this->modifyString('gurus', 'nama', 40);
        $this->modifyString('gurus', 'nuptk', 25, true);
        $this->modifyString('gurus', 'jenjang_pendidikan', 2);

        $this->modifyString('siswas', 'nama', 40);
        $this->modifyString('siswas', 'nis', 11, true);
        $this->modifyString('siswas', 'nisn', 10, true);
        $this->modifyString('siswas', 'tempat_lahir', 20, true);
        $this->modifyString('siswas', 'agama', 20, true);
        $this->modifyString('siswas', 'nama_ayah', 40, true);
        $this->modifyString('siswas', 'nama_ibu', 40, true);
        $this->modifyString('siswas', 'nama_wali', 40, true);
        $this->modifyString('siswas', 'nomor_telepon', 13, true);

        $this->modifyString('mata_pelajarans', 'nama_mapel', 40);

        $this->modifyString('tahun_ajaran', 'tahun_ajaran', 10);

        $this->modifyString('pengumuman', 'judul', 30);

        $this->modifyString('detail_perkembangans', 'kategori_aspek', 50);
        $this->modifyString('detail_perkembangans', 'nama_aspek', 20);

        $this->modifyString('perkembangan_non_akademis', 'kategori_aspek', 50);
        $this->modifyString('perkembangan_non_akademis', 'nama_aspek', 20);
    }

    public function down(): void
    {
        $this->modifyString('users', 'username', 255);
        $this->modifyString('users', 'name', 255);
        $this->modifyString('users', 'role', 255);
        $this->modifyString('users', 'password', 255);

        $this->modifyString('kelas', 'nama_kelas', 255);

        $this->modifyString('gurus', 'nama', 255);
        $this->modifyString('gurus', 'nuptk', 255, true);
        $this->modifyString('gurus', 'jenjang_pendidikan', 255);

        $this->modifyString('siswas', 'nama', 255);
        $this->modifyString('siswas', 'nis', 255, true);
        $this->modifyString('siswas', 'nisn', 255, true);
        $this->modifyString('siswas', 'tempat_lahir', 255, true);
        $this->modifyString('siswas', 'agama', 255, true);
        $this->modifyString('siswas', 'nama_ayah', 255, true);
        $this->modifyString('siswas', 'nama_ibu', 255, true);
        $this->modifyString('siswas', 'nama_wali', 255, true);
        $this->modifyString('siswas', 'nomor_telepon', 255, true);

        $this->modifyString('mata_pelajarans', 'nama_mapel', 255);

        $this->modifyString('pengumuman', 'judul', 255);

        $this->modifyString('detail_perkembangans', 'kategori_aspek', 255);
        $this->modifyString('detail_perkembangans', 'nama_aspek', 255);

        $this->modifyString('perkembangan_non_akademis', 'kategori_aspek', 255);
        $this->modifyString('perkembangan_non_akademis', 'nama_aspek', 255);
    }

    private function modifyString(string $table, string $column, int $length, bool $nullable = false, ?string $default = null): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        $nullSql = $nullable ? 'NULL' : 'NOT NULL';
        $defaultSql = $default === null ? '' : " DEFAULT {$default}";

        DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` VARCHAR({$length}) {$nullSql}{$defaultSql}");
    }
};
