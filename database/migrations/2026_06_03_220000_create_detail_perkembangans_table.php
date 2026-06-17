<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_perkembangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkembangan_id')->constrained('perkembangans')->cascadeOnDelete();
            $table->string('kategori_aspek');
            $table->string('nama_aspek');
            $table->text('hal_berkembang')->nullable();
            $table->text('perlu_diperhatikan')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_perkembangans');
    }
};
