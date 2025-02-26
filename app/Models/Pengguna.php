<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'pengguna_id';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'reset_token'
    ];

    // Relasi dengan tabel pesanan (one to many)
    public function bahan_baku_transaksi()
    {
        return $this->hasMany(BahanBakuTransaksi::class, 'dibuat_oleh', 'pengguna_id');
    }
}
