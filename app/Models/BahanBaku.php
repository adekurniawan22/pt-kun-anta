<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';
    protected $primaryKey = 'bahan_baku_id';

    protected $fillable = ['kode_bahan_baku', 'nama_bahan_baku', 'satuan', 'stok_minimal', 'harga_per_satuan', 'dibuat_oleh'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'pengguna_id');
    }

    public function transaksi()
    {
        return $this->hasMany(BahanBakuTransaksi::class, 'bahan_baku_id', 'bahan_baku_id');
    }
}
