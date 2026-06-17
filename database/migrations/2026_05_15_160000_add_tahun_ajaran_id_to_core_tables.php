<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('kelas_id')->constrained('tahun_ajaran')->nullOnDelete();
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('kelas_id')->constrained('tahun_ajaran')->nullOnDelete();
        });

        Schema::table('perkembangans', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->after('guru_id')->constrained('tahun_ajaran')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });
    }
};