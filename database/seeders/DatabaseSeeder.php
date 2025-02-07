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
        // DATA PENGGUNA
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

        // DATA SUPPLIER
        for ($i = 0; $i < 10; $i++) {
            $kontakSupplier = '628' . str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

            $jumlahBahanBaku = rand(1, 5);
            $bahanBakuArray = array_unique(array_map(function () {
                return rand(1, 10);
            }, range(1, $jumlahBahanBaku)));
            $bahanBaku = json_encode(array_values($bahanBakuArray));

            DB::table('supplier')->insert([
                'nama_supplier' => $this->namaPerusahaan[$i],
                'alamat_supplier' => $this->alamat[$i],
                'kontak_supplier' => $kontakSupplier,
                'bahan_baku' => $bahanBaku,
                'dibuat_oleh' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // DATA BAHAN BAKU
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
        foreach ($bahanBakuNames as $index => $namaBahanBaku) {
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
                'dibuat_oleh' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Ambil semua ID bahan baku yang tersedia
        $bahanBakuIds = DB::table('bahan_baku')->pluck('bahan_baku_id')->toArray();
        $penggunaIds = DB::table('pengguna')->pluck('pengguna_id')->toArray();
        $bahanBakuMasuk = [];

        for ($month = 1; $month <= 11; $month++) {
            $startDate = Carbon::create(2024, $month, 1);
            $bulanIndonesia = $this->bulanIndonesia[$startDate->format('F')];

            // Pilih bahan baku secara acak
            $selectedBahanBakuId = $bahanBakuIds[array_rand($bahanBakuIds)];
            $jumlahMasuk = rand(500, 1000);
            $hargaPerSatuan = rand(10000, 50000); // Harga per satuan ditentukan saat transaksi masuk

            // Catat bahwa bahan baku ini sudah memiliki transaksi masuk
            $bahanBakuMasuk[$selectedBahanBakuId] = true;

            // Insert transaksi masuk
            DB::table('transaksi_masuk')->insert([
                'bahan_baku_id' => $selectedBahanBakuId,
                'tanggal_transaksi' => $startDate->format('Y-m-d'),
                'jumlah' => $jumlahMasuk,
                'harga_per_satuan' => $hargaPerSatuan,
                'keterangan' => "Transaksi masuk awal bulan {$bulanIndonesia} {$startDate->format('Y')}",
                'supplier_id' => rand(1, 9),
                'dibuat_oleh' => $penggunaIds[array_rand($penggunaIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Generate transaksi keluar
            $outTransactionsCount = rand(2, 5);
            $totalKeluar = 0;

            for ($i = 0; $i < $outTransactionsCount; $i++) {
                if ($totalKeluar >= $jumlahMasuk) break;
                $validBahanBakuIds = array_keys($bahanBakuMasuk);
                $selectedKeluarBahanBakuId = $validBahanBakuIds[array_rand($validBahanBakuIds)];

                $jumlahKeluar = rand(1, min(50, $jumlahMasuk - $totalKeluar));
                DB::table('transaksi_keluar')->insert([
                    'bahan_baku_id' => $selectedKeluarBahanBakuId,
                    'tanggal_transaksi' => $startDate->format('Y-m-d'),
                    'jumlah' => $jumlahKeluar,
                    'keterangan' => "Transaksi keluar bulan {$bulanIndonesia} {$startDate->format('Y')}",
                    'dibuat_oleh' => $penggunaIds[array_rand($penggunaIds)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalKeluar += $jumlahKeluar;
            }
        }
    }

    private $bulanIndonesia = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    private $namaPerusahaan = [
        'PT. Sentosa Agrindo',
        'CV. Maju Bersama',
        'PT. Bumi Pangan Utama',
        'UD. Hasil Tani',
        'PT. Sumber Hasil Alam',
        'CV. Tani Makmur',
        'PT. Agro Jaya Mandiri',
        'UD. Tani Sejahtera',
        'PT. Pangan Nusantara',
        'CV. Hasil Bumi Indonesia'
    ];

    private $alamat = [
        'Jl. Gatot Subroto No. 123, Kuningan, Jakarta Selatan',
        'Jl. Ahmad Yani No. 45, Bekasi Barat, Bekasi',
        'Jl. Sudirman Kav. 89, Setiabudi, Jakarta Pusat',
        'Jl. Raya Bogor Km. 29, Cimanggis, Depok',
        'Jl. Industri No. 56, Cibitung, Bekasi',
        'Jl. Raya Serpong No. 78, Tangerang Selatan',
        'Jl. Veteran No. 234, Bandung',
        'Jl. Diponegoro No. 167, Surabaya Pusat',
        'Jl. Gajah Mada No. 90, Semarang',
        'Jl. Pahlawan No. 45, Medan'
    ];
}
