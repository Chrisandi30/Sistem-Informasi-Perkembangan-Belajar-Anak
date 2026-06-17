<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'password')) {
            DB::statement('ALTER TABLE `users` MODIFY `password` VARCHAR(150) NOT NULL');
        }

        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'pas_foto')) {
            DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(150) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'password')) {
            DB::statement('ALTER TABLE `users` MODIFY `password` VARCHAR(255) NOT NULL');
        }

        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'pas_foto')) {
            DB::statement('ALTER TABLE `siswas` MODIFY `pas_foto` VARCHAR(255) NULL');
        }
    }
};
