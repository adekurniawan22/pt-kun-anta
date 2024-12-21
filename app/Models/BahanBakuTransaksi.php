<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBakuTransaksi extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku_transaksi';

    protected $primaryKey = 'bahan_baku_transaksi_id';

    protected $fillable = [
        'bahan_baku_id',
        'tipe',
        'tanggal_transaksi',
        'jumlah',
        'total',
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
