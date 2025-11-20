<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->id('detail_id'); // primary key
            $table->unsignedBigInteger('penjualan_id'); // foreign key ke penjualans
            $table->unsignedBigInteger('product_id');   // foreign key ke products
            $table->integer('jumlah_product')->default(1);
            $table->integer('subtotal')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('penjualan_id')->references('penjualan_id')->on('penjualans')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_details');
    }
};return redirect()->route('admin.setting')->with('success', 'Pengguna dihapus');