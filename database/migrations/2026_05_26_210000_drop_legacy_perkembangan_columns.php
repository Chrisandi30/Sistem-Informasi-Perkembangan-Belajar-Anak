<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('perkembangans')
            ->whereNull('keterampilan_sosial_sudah')
            ->whereNotNull('keterampilan_sosial')
            ->update(['keterampilan_sosial_sudah' => DB::raw('keterampilan_sosial')]);

        DB::table('perkembangans')
            ->whereNull('sikap_perilaku_sudah')
            ->whereNotNull('sikap_perilaku')
            ->update(['sikap_perilaku_sudah' => DB::raw('sikap_perilaku')]);

        DB::table('perkembangans')
            ->whereNull('pengontrolan_emosi_sudah')
            ->whereNotNull('pengontrolan_emosi')
            ->update(['pengontrolan_emosi_sudah' => DB::raw('pengontrolan_emosi')]);

        DB::table('perkembangans')
            ->whereNull('kemandirian_sudah')
            ->whereNotNull('kemandirian_deskripsi')
            ->update(['kemandirian_sudah' => DB::raw('kemandirian_deskripsi')]);

        DB::table('perkembangans')
            ->whereNull('kematangan_sudah')
            ->whereNotNull('kematangan_deskripsi')
            ->update(['kematangan_sudah' => DB::raw('kematangan_deskripsi')]);

        Schema::table('perkembangans', function (Blueprint $table) {
            $table->dropColumn([
                'keterampilan_sosial',
                'sikap_perilaku',
                'pengontrolan_emosi',
                'kemandirian_deskripsi',
                'kematangan_deskripsi',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            $table->text('keterampilan_sosial')->nullable()->after('tahun');
            $table->text('sikap_perilaku')->nullable()->after('keterampilan_sosial');
            $table->text('pengontrolan_emosi')->nullable()->after('sikap_perilaku');
            $table->text('kemandirian_deskripsi')->nullable()->after('pengontrolan_emosi');
            $table->text('kematangan_deskripsi')->nullable()->after('kemandirian_deskripsi');
        });

        DB::table('perkembangans')->update([
            'keterampilan_sosial' => DB::raw('keterampilan_sosial_sudah'),
            'sikap_perilaku' => DB::raw('sikap_perilaku_sudah'),
            'pengontrolan_emosi' => DB::raw('pengontrolan_emosi_sudah'),
            'kemandirian_deskripsi' => DB::raw('kemandirian_sudah'),
            'kematangan_deskripsi' => DB::raw('kematangan_sudah'),
        ]);
    }
};
