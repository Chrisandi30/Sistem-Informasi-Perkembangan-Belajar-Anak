<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('gurus') && Schema::hasColumn('gurus', 'user_id')) {
            DB::statement('ALTER TABLE gurus DROP FOREIGN KEY gurus_user_id_foreign');
            DB::statement('ALTER TABLE gurus MODIFY user_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE gurus ADD CONSTRAINT gurus_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }

        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'user_id')) {
            DB::statement('ALTER TABLE siswas DROP FOREIGN KEY siswas_user_id_foreign');
            DB::statement('ALTER TABLE siswas MODIFY user_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE siswas ADD CONSTRAINT siswas_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('gurus') && Schema::hasColumn('gurus', 'user_id')) {
            DB::statement('DELETE FROM gurus WHERE user_id IS NULL');
            DB::statement('ALTER TABLE gurus DROP FOREIGN KEY gurus_user_id_foreign');
            DB::statement('ALTER TABLE gurus MODIFY user_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE gurus ADD CONSTRAINT gurus_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        }

        if (Schema::hasTable('siswas') && Schema::hasColumn('siswas', 'user_id')) {
            DB::statement('DELETE FROM siswas WHERE user_id IS NULL');
            DB::statement('ALTER TABLE siswas DROP FOREIGN KEY siswas_user_id_foreign');
            DB::statement('ALTER TABLE siswas MODIFY user_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE siswas ADD CONSTRAINT siswas_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        }
    }
};
