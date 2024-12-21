<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed "pengguna" table
        DB::table('pengguna')->insert([
            [
                'nama' => 'Fajar Rusdianto',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Panji Raksawijaya',
                'email' => 'manajer@example.com',
                'password' => Hash::make('password'),
                'role' => 'manajer_produksi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Asep Cimahi',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('password'),
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Menambahkan 10 supplier
        for ($i = 0; $i < 10; $i++) {
            $kontakSupplier = '628' . str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

            $jumlahBahanBaku = rand(1, 5);
            $bahanBakuArray = array_unique(array_map(function () {
                return rand(1, 10);
            }, range(1, $jumlahBahanBaku)));
            $bahanBaku = json_encode(array_values($bahanBakuArray));

            DB::table('supplier')->insert([
                'nama_supplier' => 'Supplier ' . Str::random(5),
                'alamat_supplier' => 'Alamat ' . Str::random(10),
                'kontak_supplier' => $kontakSupplier,
                'bahan_baku' => $bahanBaku,
                'dibuat_oleh' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Daftar nama bahan baku untuk perusahaan makanan
        $bahanBakuNames = [
            'Gula Pasir',
            'Tepung Terigu',
            'Minyak Goreng',
            'Garam Halus',
            'Beras Premium',
            'Kacang Tanah',
            'Coklat Bubuk',
            'Susu Bubuk',
            'Ragi Instan',
            'Vanili Bubuk',
            'Pasir',
        ];

        // Menambahkan bahan baku ke dalam tabel
        foreach ($bahanBakuNames as $index => $namaBahanBaku) {
            // Membuat kode_bahan_baku dengan format BB-XXYY-001
            $words = explode(' ', $namaBahanBaku);
            $kodeNama = '';

            foreach ($words as $word) {
                if (strlen($word) >= 2) {
                    $kodeNama .= substr($word, 0, 2);
                } else {
                    $kodeNama .= $word[0];
                }
            }

            $id = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $kodeBahanBaku = 'BB-' . strtoupper($kodeNama) . '-' . $id;

            DB::table('bahan_baku')->insert([
                'kode_bahan_baku' => $kodeBahanBaku,
                'nama_bahan_baku' => $namaBahanBaku,
                'satuan' => ['Kilogram', 'Liter'][array_rand(['Kilogram', 'Liter'])],
                'stok_minimal' => rand(10, 50),
                'harga_per_satuan' => rand(15000, 150000),
                'dibuat_oleh' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Menambahkan data 11 bulan transaksi bahan baku
        $bahanBaku = DB::table('bahan_baku')->pluck('harga_per_satuan', 'bahan_baku_id')->toArray();
        $penggunaIds = DB::table('pengguna')->pluck('pengguna_id')->toArray();

        // Simpan daftar bahan baku yang pernah masuk
        $bahanBakuMasuk = [];

        for ($month = 1; $month <= 11; $month++) {
            $startDate = Carbon::create(2024, $month, 1);
            $jumlahMasuk = rand(500, 1000);

            // Pilih bahan baku secara acak
            $selectedBahanBakuId = array_rand($bahanBaku);
            $selectedHargaPerSatuan = $bahanBaku[$selectedBahanBakuId];
            $bahanBakuMasuk[$selectedBahanBakuId] = true;

            // Membuat transaksi masuk di awal bulan
            DB::table('bahan_baku_transaksi')->insert([
                'bahan_baku_id' => $selectedBahanBakuId,
                'tipe' => 'masuk',
                'tanggal_transaksi' => $startDate->format('Y-m-d'),
                'jumlah' => $jumlahMasuk,
                'total' => $jumlahMasuk * $selectedHargaPerSatuan,
                'keterangan' => 'Transaksi masuk awal bulan ' . $startDate->format('F Y'),
                'supplier_id' => rand(1, 9),
                'dibuat_oleh' => $penggunaIds[array_rand($penggunaIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $outTransactionsCount = rand(2, 5);
            $totalKeluar = 0;

            for ($i = 0; $i < $outTransactionsCount; $i++) {
                if ($totalKeluar >= $jumlahMasuk) break;

                // Pilih bahan baku yang pernah masuk
                $validBahanBakuIds = array_keys($bahanBakuMasuk);
                $selectedKeluarBahanBakuId = $validBahanBakuIds[array_rand($validBahanBakuIds)];
                $selectedKeluarHargaPerSatuan = $bahanBaku[$selectedKeluarBahanBakuId];

                $jumlahKeluar = rand(1, min(50, $jumlahMasuk - $totalKeluar));

                // Tambahkan transaksi keluar
                DB::table('bahan_baku_transaksi')->insert([
                    'bahan_baku_id' => $selectedKeluarBahanBakuId,
                    'tipe' => 'keluar',
                    'tanggal_transaksi' => $startDate->format('Y-m-d'),
                    'jumlah' => $jumlahKeluar,
                    'total' => null,
                    'keterangan' => 'Transaksi keluar bulan ' . $startDate->format('F Y'),
                    'dibuat_oleh' => $penggunaIds[array_rand($penggunaIds)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalKeluar += $jumlahKeluar;
            }
        }
    }
}
