<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (! Schema::hasColumn('siswas', 'nama_ayah')) {
                $table->string('nama_ayah')->nullable()->after('jenis_kelamin');
            }

            if (! Schema::hasColumn('siswas', 'nama_ibu')) {
                $table->string('nama_ibu')->nullable()->after('nama_ayah');
            }
        });

        if (Schema::hasColumn('siswas', 'nama_orang_tua')) {
            DB::table('siswas')
                ->whereNull('nama_ayah')
                ->update([
                    'nama_ayah' => DB::raw('nama_orang_tua'),
                ]);
        }

        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'nama_orang_tua')) {
                $table->dropColumn('nama_orang_tua');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (! Schema::hasColumn('siswas', 'nama_orang_tua')) {
                $table->string('nama_orang_tua')->nullable()->after('jenis_kelamin');
            }
        });

        DB::table('siswas')->update([
            'nama_orang_tua' => DB::raw("COALESCE(NULLIF(nama_ayah, ''), NULLIF(nama_ibu, ''))"),
        ]);

        Schema::table('siswas', function (Blueprint $table) {
            if (Schema::hasColumn('siswas', 'nama_ayah')) {
                $table->dropColumn('nama_ayah');
            }

            if (Schema::hasColumn('siswas', 'nama_ibu')) {
                $table->dropColumn('nama_ibu');
            }
        });
    }
};
