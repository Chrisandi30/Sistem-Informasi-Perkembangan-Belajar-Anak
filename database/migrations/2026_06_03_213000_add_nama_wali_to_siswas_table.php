<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('siswas', 'nama_wali')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->string('nama_wali')->nullable()->after('nama_ibu');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('siswas', 'nama_wali')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->dropColumn('nama_wali');
            });
        }
    }
};
