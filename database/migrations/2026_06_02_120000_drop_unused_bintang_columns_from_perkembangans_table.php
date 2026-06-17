<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('perkembangans', 'kemandirian_bintang')) {
                $columnsToDrop[] = 'kemandirian_bintang';
            }

            if (Schema::hasColumn('perkembangans', 'kematangan_bintang')) {
                $columnsToDrop[] = 'kematangan_bintang';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            if (! Schema::hasColumn('perkembangans', 'kemandirian_bintang')) {
                $table->unsignedTinyInteger('kemandirian_bintang')->nullable()->after('kemandirian_belum');
            }

            if (! Schema::hasColumn('perkembangans', 'kematangan_bintang')) {
                $table->unsignedTinyInteger('kematangan_bintang')->nullable()->after('kematangan_belum');
            }
        });
    }
};
