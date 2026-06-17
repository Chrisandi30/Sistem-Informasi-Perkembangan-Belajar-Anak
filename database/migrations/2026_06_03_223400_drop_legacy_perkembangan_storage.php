<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('perkembangans')) {
            $columnsToDrop = [];

            foreach ([
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
            ] as $column) {
                if (Schema::hasColumn('perkembangans', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if ($columnsToDrop !== []) {
                Schema::table('perkembangans', function (Blueprint $table) use ($columnsToDrop) {
                    $table->dropColumn($columnsToDrop);
                });
            }
        }

        Schema::dropIfExists('perkembangan_akademis');
    }

    public function down(): void
    {
        if (! Schema::hasTable('perkembangans')) {
            return;
        }

        Schema::table('perkembangans', function (Blueprint $table) {
            if (! Schema::hasColumn('perkembangans', 'keterampilan_sosial_sudah')) {
                $table->text('keterampilan_sosial_sudah')->nullable()->after('tahun');
            }
            if (! Schema::hasColumn('perkembangans', 'keterampilan_sosial_belum')) {
                $table->text('keterampilan_sosial_belum')->nullable()->after('keterampilan_sosial_sudah');
            }
            if (! Schema::hasColumn('perkembangans', 'sikap_perilaku_sudah')) {
                $table->text('sikap_perilaku_sudah')->nullable()->after('keterampilan_sosial_belum');
            }
            if (! Schema::hasColumn('perkembangans', 'sikap_perilaku_belum')) {
                $table->text('sikap_perilaku_belum')->nullable()->after('sikap_perilaku_sudah');
            }
            if (! Schema::hasColumn('perkembangans', 'pengontrolan_emosi_sudah')) {
                $table->text('pengontrolan_emosi_sudah')->nullable()->after('sikap_perilaku_belum');
            }
            if (! Schema::hasColumn('perkembangans', 'pengontrolan_emosi_belum')) {
                $table->text('pengontrolan_emosi_belum')->nullable()->after('pengontrolan_emosi_sudah');
            }
            if (! Schema::hasColumn('perkembangans', 'kemandirian_sudah')) {
                $table->text('kemandirian_sudah')->nullable()->after('pengontrolan_emosi_belum');
            }
            if (! Schema::hasColumn('perkembangans', 'kemandirian_belum')) {
                $table->text('kemandirian_belum')->nullable()->after('kemandirian_sudah');
            }
            if (! Schema::hasColumn('perkembangans', 'kematangan_sudah')) {
                $table->text('kematangan_sudah')->nullable()->after('kemandirian_belum');
            }
            if (! Schema::hasColumn('perkembangans', 'kematangan_belum')) {
                $table->text('kematangan_belum')->nullable()->after('kematangan_sudah');
            }
        });

        if (! Schema::hasTable('perkembangan_akademis')) {
            Schema::create('perkembangan_akademis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('perkembangan_id')->constrained('perkembangans')->cascadeOnDelete();
                $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
                $table->longText('deskripsi_sudah')->nullable();
                $table->longText('deskripsi_belum')->nullable();
                $table->longText('deskripsi')->nullable();
                $table->timestamps();
                $table->unique(['perkembangan_id', 'mata_pelajaran_id']);
            });
        }
    }
};
