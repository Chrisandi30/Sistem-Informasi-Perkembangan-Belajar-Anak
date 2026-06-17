<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('tanggal_terbit');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_mulai');
        });

        DB::table('pengumuman')->update([
            'tanggal_mulai' => DB::raw('tanggal_terbit'),
            'tanggal_berakhir' => DB::raw('tanggal_terbit'),
        ]);

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable(false)->change();
            $table->date('tanggal_berakhir')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_berakhir']);
        });
    }
};
