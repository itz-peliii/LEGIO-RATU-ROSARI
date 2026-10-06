<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_member_id')->constrained('members')->cascadeOnDelete();
            $table->date('visit_date');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'batal'])->default('terjadwal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_schedules');
    }
};
