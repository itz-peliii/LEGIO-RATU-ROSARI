<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel "reading_overrides" = penimpaan manual halaman untuk minggu tertentu.
 * week_start = tanggal awal minggu yang ditimpa (dihitung dari start_date buku).
 * Disimpan sebagai TANGGAL (bukan nomor minggu) agar tetap stabil
 * walau start_date buku nanti diubah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_id')->constrained()->cascadeOnDelete();
            $table->date('week_start');
            $table->unsignedInteger('page');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['reading_id', 'week_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_overrides');
    }
};
