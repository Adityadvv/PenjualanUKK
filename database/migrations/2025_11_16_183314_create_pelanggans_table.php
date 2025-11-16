<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

     public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id('pelanggan_id');
            $table->string('namapelanggan');
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->enum('tipe_pesanan', ['dine_in', 'take_away'])->default('take_away');
            $table->integer('nomor_meja')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};