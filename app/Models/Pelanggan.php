<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = 'pelanggan_id';

    protected $fillable = [
        'namapelanggan',
        'alamat',
        'no_telepon',
        'tipe_pesanan',
        'nomor_meja',
    ];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }
}
