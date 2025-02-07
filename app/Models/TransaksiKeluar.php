<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keluar';

    protected $primaryKey = 'transaksi_keluar_id';

    protected $fillable = [
        'bahan_baku_id',
        'tanggal_transaksi',
        'jumlah',
        'keterangan',
        'dibuat_oleh'
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id', 'bahan_baku_id');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'pengguna_id');
    }
}
