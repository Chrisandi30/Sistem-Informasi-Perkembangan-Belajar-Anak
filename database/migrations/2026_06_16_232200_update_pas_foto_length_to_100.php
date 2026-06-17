<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'pas_foto')) {
            DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(100) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'pas_foto')) {
            DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(150) NULL');
        }
    }
};
