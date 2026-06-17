<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('guru_id')->constrained('kelas')->nullOnDelete();
        });

        DB::table('perkembangans')
            ->join('siswas', 'siswas.id', '=', 'perkembangans.siswa_id')
            ->update([
                'perkembangans.kelas_id' => DB::raw('siswas.kelas_id'),
            ]);
    }

    public function down(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kelas_id');
        });
    }
};