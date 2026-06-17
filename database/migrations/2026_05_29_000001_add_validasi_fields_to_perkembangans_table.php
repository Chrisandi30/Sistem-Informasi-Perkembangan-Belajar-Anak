<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            if (! Schema::hasColumn('perkembangans', 'status')) {
                $table->enum('status', ['menunggu', 'disetujui', 'revisi'])->default('menunggu')->after('tahun');
            }

            if (! Schema::hasColumn('perkembangans', 'validated_by')) {
                $table->foreignId('validated_by')
                    ->nullable()
                    ->after('status')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('perkembangans', 'validated_at')) {
                $table->timestamp('validated_at')->nullable()->after('validated_by');
            }

            if (! Schema::hasColumn('perkembangans', 'catatan_validasi')) {
                $table->text('catatan_validasi')->nullable()->after('validated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('perkembangans', function (Blueprint $table) {
            if (Schema::hasColumn('perkembangans', 'validated_by')) {
                $table->dropConstrainedForeignId('validated_by');
            }

            if (Schema::hasColumn('perkembangans', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('perkembangans', 'validated_at')) {
                $table->dropColumn('validated_at');
            }

            if (Schema::hasColumn('perkembangans', 'catatan_validasi')) {
                $table->dropColumn('catatan_validasi');
            }
        });
    }
};
