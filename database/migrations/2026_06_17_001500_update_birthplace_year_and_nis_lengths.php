<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('siswas')) {
            if (Schema::hasColumn('siswas', 'nis')) {
                DB::statement('ALTER TABLE `siswas` MODIFY `nis` VARCHAR(11) NULL');
                DB::statement("UPDATE `siswas` SET `nis` = CONCAT(SUBSTRING(`nis`, 1, 1), '.', SUBSTRING(`nis`, 2, 2), '.', SUBSTRING(`nis`, 4, 3), '.', SUBSTRING(`nis`, 7, 2)) WHERE `nis` REGEXP '^[0-9]{8}$'");
            }

            if (Schema::hasColumn('siswas', 'tempat_lahir')) {
                DB::statement('ALTER TABLE `siswas` MODIFY `tempat_lahir` VARCHAR(20) NULL');
            }
        }

        if (Schema::hasTable('tahun_ajaran') && Schema::hasColumn('tahun_ajaran', 'tahun_ajaran')) {
            DB::statement('ALTER TABLE `tahun_ajaran` MODIFY `tahun_ajaran` VARCHAR(10) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('siswas')) {
            if (Schema::hasColumn('siswas', 'nis')) {
                DB::statement("UPDATE `siswas` SET `nis` = REPLACE(`nis`, '.', '') WHERE `nis` REGEXP '^[0-9][.][0-9]{2}[.][0-9]{3}[.][0-9]{2}$'");
                DB::statement('ALTER TABLE `siswas` MODIFY `nis` VARCHAR(8) NULL');
            }

            if (Schema::hasColumn('siswas', 'tempat_lahir')) {
                DB::statement('ALTER TABLE `siswas` MODIFY `tempat_lahir` VARCHAR(50) NULL');
            }
        }

        if (Schema::hasTable('tahun_ajaran') && Schema::hasColumn('tahun_ajaran', 'tahun_ajaran')) {
            DB::statement('ALTER TABLE `tahun_ajaran` MODIFY `tahun_ajaran` VARCHAR(20) NOT NULL');
        }
    }
};
