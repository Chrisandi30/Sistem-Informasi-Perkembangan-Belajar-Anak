<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('perkembangans', 'tahun_ajaran_id')) {
            return;
        }

        Schema::table('perkembangans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('perkembangans', 'tahun_ajaran_id')) {
            return;
        }

        Schema::table('perkembangans', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')
                ->nullable()
                ->after('guru_id')
                ->constrained('tahun_ajaran')
                ->nullOnDelete();
        });
    }
};
