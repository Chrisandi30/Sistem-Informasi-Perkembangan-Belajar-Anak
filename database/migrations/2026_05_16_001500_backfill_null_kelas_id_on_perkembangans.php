<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('perkembangans')
            ->join('gurus', 'gurus.id', '=', 'perkembangans.guru_id')
            ->whereNull('perkembangans.kelas_id')
            ->update([
                'perkembangans.kelas_id' => DB::raw('gurus.kelas_id'),
            ]);
    }

    public function down(): void
    {
    }
};