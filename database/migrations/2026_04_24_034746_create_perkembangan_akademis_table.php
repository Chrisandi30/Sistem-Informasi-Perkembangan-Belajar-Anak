<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkembangan_akademis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkembangan_id')->constrained('perkembangans')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->restrictOnDelete();
            $table->longText('deskripsi');
            $table->timestamps();

            $table->unique(['perkembangan_id', 'mata_pelajaran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkembangan_akademis');
    }
};
