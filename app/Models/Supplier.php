<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_supplier',
        'nama_pic',
        'email',
        'no_telp',
        'alamat',
        'keterangan'
    ];

    // Relasi: Supplier bisa memiliki banyak Produk
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
