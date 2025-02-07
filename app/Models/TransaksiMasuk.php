<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
    use HasFactory;

    protected $table = 'transaksi_masuk';

    protected $primaryKey = 'transaksi_masuk_id';

    protected $fillable = [
        'bahan_baku_id',
        'tanggal_transaksi',
        'jumlah',
        'harga_per_satuan',
        'keterangan',
        'supplier_id',
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}
