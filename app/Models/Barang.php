<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'barangs'; 

    protected $fillable = [
        'nama_barang',
        'harga_per_kg',
        'qty',
        'detail_barang',
        'supplier_id'
    ];

    // Relasi: Barang dimiliki oleh satu Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
