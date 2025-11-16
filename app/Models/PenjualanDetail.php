<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'detail_id';

    protected $fillable = [
        'penjualan_id',
        'product_id',
        'jumlah_product',
        'subtotal',
    ];

    // Relasi ke Penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Helper untuk mengambil tanggal penjualan langsung dari relasi Penjualan
    public function getTanggalPenjualanAttribute()
    {
        return $this->penjualan->tanggal_penjualan ?? null;
    }
}