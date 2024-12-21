<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'nama_supplier',
        'alamat_supplier',
        'kontak_supplier',
        'bahan_baku',
        'dibuat_oleh',
    ];

    // Menentukan bahwa kolom bahan_baku disimpan dalam format array atau JSON
    protected $casts = [
        'bahan_baku' => 'array',
    ];

    // Relasi dengan tabel pengguna (pengguna_id)
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh');
    }

    public function transaksi()
    {
        return $this->hasMany(BahanBakuTransaksi::class, 'supplier_id', 'supplier_id');
    }
}
