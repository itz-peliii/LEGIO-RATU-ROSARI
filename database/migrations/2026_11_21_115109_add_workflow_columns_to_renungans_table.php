<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom alur draft → pending → approved/rejected
     * yang dipakai model Renungan tapi belum ada di tabel.
     * Setiap kolom dicek dulu, jadi aman dijalankan walau sebagian sudah ada.
     */
    public function up(): void
    {
        Schema::table('renungans', function (Blueprint $table) {
            if (! Schema::hasColumn('renungans', 'status')) {
                $table->string('status', 20)->default('draft')->index();
            }
            if (! Schema::hasColumn('renungans', 'week_number')) {
                $table->unsignedSmallInteger('week_number')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'last_edited_at')) {
                $table->timestamp('last_edited_at')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('renungans', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'reject_reason')) {
                $table->text('reject_reason')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'hidden_at')) {
                $table->timestamp('hidden_at')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'hidden_by')) {
                $table->foreignId('hidden_by')->nullable()->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('renungans', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            if (! Schema::hasColumn('renungans', 'display_days')) {
                $table->unsignedSmallInteger('display_days')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('renungans', function (Blueprint $table) {
            foreach (['approved_by', 'hidden_by'] as $fk) {
                if (Schema::hasColumn('renungans', $fk)) {
                    $table->dropConstrainedForeignId($fk);
                }
            }

            $kolom = array_filter(
                ['last_edited_at', 'approved_at', 'rejected_at', 'reject_reason', 'hidden_at', 'expires_at', 'display_days'],
                fn ($c) => Schema::hasColumn('renungans', $c)
            );

            if ($kolom) {
                $table->dropColumn($kolom);
            }
        });
    }
};
