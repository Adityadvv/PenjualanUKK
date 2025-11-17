<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('mejas', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_meja')->unique();
            $table->enum('status', ['tersedia', 'dipakai'])->default('tersedia');
            $table->string('oleh')->nullable(); // nama pelanggan jika sedang dipakai
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('mejas');
    }
};
