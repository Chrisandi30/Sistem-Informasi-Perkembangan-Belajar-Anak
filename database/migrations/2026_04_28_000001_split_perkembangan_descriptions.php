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
            $table->text('keterampilan_sosial_sudah')->nullable()->after('tahun');
            $table->text('keterampilan_sosial_belum')->nullable()->after('keterampilan_sosial_sudah');
            $table->text('sikap_perilaku_sudah')->nullable()->after('keterampilan_sosial_belum');
            $table->text('sikap_perilaku_belum')->nullable()->after('sikap_perilaku_sudah');
            $table->text('pengontrolan_emosi_sudah')->nullable()->after('sikap_perilaku_belum');
            $table->text('pengontrolan_emosi_belum')->nullable()->after('pengontrolan_emosi_sudah');
            $table->text('kemandirian_sudah')->nullable()->after('pengontrolan_emosi_belum');
            $table->text('kemandirian_belum')->nullable()->after('kemandirian_sudah');
            $table->text('kematangan_sudah')->nullable()->after('kemandirian_bintang');
            $table->text('kematangan_belum')->nullable()->after('kematangan_sudah');
        });

        Schema::table('perkembangan_akademis', function (Blueprint $table) {
            $table->longText('deskripsi_sudah')->nullable()->after('mata_pelajaran_id');
            $table->longText('deskripsi_belum')->nullable()->after('deskripsi_sudah');
        });

        DB::table('perkembangans')->update([
            'keterampilan_sosial_sudah' => DB::raw('keterampilan_sosial'),
            'sikap_perilaku_sudah' => DB::raw('sikap_perilaku'),
            'pengontrolan_emosi_sudah' => DB::raw('pengontrolan_emosi'),
            'kemandirian_sudah' => DB::raw('kemandirian_deskripsi'),
            'kematangan_sudah' => DB::raw('kematangan_deskripsi'),
        ]);

        DB::table('perkembangan_akademis')->update([
            'deskripsi_sudah' => DB::raw('deskripsi'),
        ]);
    }

    public function down(): void
    {
        Schema::table('perkembangan_akademis', function (Blueprint $table) {
            $table->dropColumn(['deskripsi_sudah', 'deskripsi_belum']);
        });

        Schema::table('perkembangans', function (Blueprint $table) {
            $table->dropColumn([
                'keterampilan_sosial_sudah',
                'keterampilan_sosial_belum',
                'sikap_perilaku_sudah',
                'sikap_perilaku_belum',
                'pengontrolan_emosi_sudah',
                'pengontrolan_emosi_belum',
                'kemandirian_sudah',
                'kemandirian_belum',
                'kematangan_sudah',
                'kematangan_belum',
            ]);
        });
    }
};
