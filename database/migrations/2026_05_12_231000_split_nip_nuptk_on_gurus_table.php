<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('nama');
            $table->string('nuptk')->nullable()->after('nip');
        });

        DB::table('gurus')
            ->whereNull('nip')
            ->update([
                'nip' => DB::raw('nip_nuptk'),
            ]);
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['nip', 'nuptk']);
        });
    }
};