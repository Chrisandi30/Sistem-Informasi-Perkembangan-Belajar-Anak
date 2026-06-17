<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangan_akademis', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->foreign('mata_pelajaran_id')
                ->references('id')
                ->on('mata_pelajarans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perkembangan_akademis', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->foreign('mata_pelajaran_id')
                ->references('id')
                ->on('mata_pelajarans')
                ->restrictOnDelete();
        });
    }
};
