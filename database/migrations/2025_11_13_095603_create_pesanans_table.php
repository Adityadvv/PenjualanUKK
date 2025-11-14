<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create pesanans table first so pesanan_details can reference it
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            // pelanggan_id in pelanggans is a string primary key, so match that type
            $table->string('pelanggan_id');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('pelanggan_id')->references('pelanggan_id')->on('pelanggans')->onDelete('cascade');
        });

        Schema::create('pesanan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesanan_id'); // Foreign key ke pesanans
            $table->unsignedBigInteger('product_id'); // Foreign key ke products
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2); // Harga pada saat pesan
            $table->timestamps();

            $table->foreign('pesanan_id')->references('id')->on('pesanans')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Drop details first to avoid FK constraint issues
        Schema::dropIfExists('pesanan_details');
        Schema::dropIfExists('pesanans');
    }
};