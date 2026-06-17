<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('siswas', 'nomor_whatsapp') && ! Schema::hasColumn('siswas', 'nomor_telepon')) {
            DB::statement('ALTER TABLE siswas CHANGE nomor_whatsapp nomor_telepon VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('siswas', 'nomor_telepon') && ! Schema::hasColumn('siswas', 'nomor_whatsapp')) {
            DB::statement('ALTER TABLE siswas CHANGE nomor_telepon nomor_whatsapp VARCHAR(255) NULL');
        }
    }
};
