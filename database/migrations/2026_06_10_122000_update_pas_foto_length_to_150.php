<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(150) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(255) NULL');
    }
};
