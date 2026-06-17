<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE siswas CHANGE nik nis VARCHAR(11) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE siswas CHANGE nis nik VARCHAR(255) NULL');
    }
};
