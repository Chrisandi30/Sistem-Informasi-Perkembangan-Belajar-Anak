<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('whats_app_logs');
    }

    public function down(): void
    {
        Schema::create('whats_app_logs', function ($table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->string('nomor_tujuan');
            $table->text('pesan');
            $table->string('status')->default('pending');
            $table->longText('response')->nullable();
            $table->timestamps();
        });
    }
};
