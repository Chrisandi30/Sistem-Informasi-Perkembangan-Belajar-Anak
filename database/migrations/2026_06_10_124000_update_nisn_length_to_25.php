<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `siswas` MODIFY `nis` VARCHAR(25) NULL');
        DB::statement('ALTER TABLE `siswas` MODIFY `nisn` VARCHAR(25) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `siswas` MODIFY `nis` VARCHAR(25) NULL');
        DB::statement('ALTER TABLE `siswas` MODIFY `nisn` VARCHAR(20) NULL');
    }
};
