<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kegiatan_user', function (Blueprint $table) {
            $table->unsignedTinyInteger('remind_days')->default(1); // H-berapa
            $table->timestamp('reminded_at')->nullable();           // supaya tidak terkirim dobel
        });

        if (! Schema::hasColumn('users', 'phone')) {
            Schema::table('users', fn(Blueprint $t) => $t->string('phone', 20)->nullable());
        }
    }

    public function down(): void
    {
        Schema::table('kegiatan_user', fn(Blueprint $t) => $t->dropColumn(['remind_days', 'reminded_at']));
    }
};
