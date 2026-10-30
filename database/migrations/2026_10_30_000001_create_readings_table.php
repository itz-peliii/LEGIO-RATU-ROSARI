<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel "readings" = buku/PDF sumber bacaan renungan.
 * Biasanya hanya ada 1 yang aktif (is_active = true).
 *
 * Mekanisme halaman minggu ini (otomatis):
 *   halaman = start_page + (nomor_minggu * pages_per_week)
 *   nomor_minggu dihitung dari selisih (hari ini - start_date) / 7
 * Untuk minggu tertentu, admin bisa menimpanya lewat tabel reading_overrides.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('readings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Bacaan Renungan');
            $table->string('file_path');                       // path di disk "public", mis. bacaan/xxxx.pdf
            $table->unsignedInteger('total_pages')->nullable(); // diisi admin (opsional) utk membatasi halaman
            $table->date('start_date');                         // tanggal mulai minggu ke-1
            $table->unsignedInteger('start_page')->default(1);  // halaman untuk minggu ke-1
            $table->unsignedInteger('pages_per_week')->default(1); // berapa halaman maju tiap minggu
            $table->boolean('is_active')->default(true);
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};
