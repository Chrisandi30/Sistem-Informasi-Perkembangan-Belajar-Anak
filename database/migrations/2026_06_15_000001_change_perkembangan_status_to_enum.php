<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('perkembangans') || ! Schema::hasColumn('perkembangans', 'status')) {
            return;
        }

        DB::table('perkembangans')
            ->where('status', 'menunggu_validasi')
            ->update(['status' => 'menunggu']);

        DB::statement("ALTER TABLE `perkembangans` MODIFY `status` ENUM('menunggu', 'disetujui', 'revisi') NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('perkembangans') || ! Schema::hasColumn('perkembangans', 'status')) {
            return;
        }

        DB::statement("ALTER TABLE `perkembangans` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'menunggu_validasi'");

        DB::table('perkembangans')
            ->where('status', 'menunggu')
            ->update(['status' => 'menunggu_validasi']);
    }
};
