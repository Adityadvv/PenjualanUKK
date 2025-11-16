<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

        public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id('penjualan_id'); // primary key
            $table->string('kode_penjualan')->unique();

            // foreign key ke pelanggans
            $table->unsignedBigInteger('pelanggan_id');
            $table->foreign('pelanggan_id')->references('pelanggan_id')->on('pelanggans')->onDelete('cascade');

            $table->dateTime('tanggal_penjualan');
            $table->integer('total_harga')->default(0);

            $table->enum('status_pembayaran', ['belum_terbayar', 'terbayar'])->default('belum_terbayar');
            $table->enum('metode_pembayaran', ['cash', 'qris'])->nullable();

            $table->timestamps();
        });
    }


     public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};