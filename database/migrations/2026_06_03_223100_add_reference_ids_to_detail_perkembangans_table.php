<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_perkembangans', function (Blueprint $table) {
            $table->foreignId('mata_pelajaran_id')->nullable()->after('perkembangan_id')->constrained('mata_pelajarans')->nullOnDelete();
            $table->foreignId('perkembangan_non_akademis_id')->nullable()->after('mata_pelajaran_id')->constrained('perkembangan_non_akademis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_perkembangans', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropForeign(['perkembangan_non_akademis_id']);
            $table->dropColumn(['mata_pelajaran_id', 'perkembangan_non_akademis_id']);
        });
    }
};
