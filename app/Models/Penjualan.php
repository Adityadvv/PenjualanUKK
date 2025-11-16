<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $primaryKey = 'penjualan_id';

    protected $fillable = [
        'pelanggan_id',
        'tanggal_penjualan',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // Ambil ID terakhir
            $lastId = Penjualan::max('penjualan_id') ?? 0;
            $next = $lastId + 1;

            // Generate RN-0001 dll
            $model->kode_penjualan = 'RN-' . str_pad($next, 4, '0', STR_PAD_LEFT);

            //Tanggal otomatis terisi
            $model->tanggal_penjualan = now();
        });
    }

    protected $casts = [
    'tanggal_penjualan' => 'datetime',
    ];



    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

}
