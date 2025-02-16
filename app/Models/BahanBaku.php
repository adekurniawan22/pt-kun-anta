<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';
    protected $primaryKey = 'bahan_baku_id';

    protected $fillable = ['kode_bahan_baku', 'nama_bahan_baku', 'satuan', 'stok_minimal', 'dibuat_oleh'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'pengguna_id');
    }

    public function transaksi_masuk()
    {
        return $this->hasMany(TransaksiMasuk::class, 'bahan_baku_id');
    }

    public function transaksi_keluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'bahan_baku_id');
    }

    protected static $namaBulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    private static function formatBulan($date)
    {
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        return self::$namaBulan[$bulan] . ' ' . $tahun;
    }

    public static function getAllDetailedStock($numberOfMonths = 15, $startDate = null)
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now();

        $dates = collect();
        for ($i = ($numberOfMonths + 1) - 1; $i >= 1; $i--) {
            $dates->push($startDate->copy()->startOfMonth()->subMonths($i));
        }

        $bahanBaku = self::all();

        $currentStocks = DB::table('bahan_baku as bb')
            ->select('bb.bahan_baku_id', DB::raw('
            (COALESCE(masuk.total_masuk, 0) - COALESCE(keluar.total_keluar, 0)) as total_stok
        '))
            ->leftJoin(DB::raw('(
            SELECT bahan_baku_id, SUM(jumlah) as total_masuk
            FROM transaksi_masuk
            GROUP BY bahan_baku_id
        ) masuk'), 'masuk.bahan_baku_id', '=', 'bb.bahan_baku_id')
            ->leftJoin(DB::raw('(
            SELECT bahan_baku_id, SUM(jumlah) as total_keluar 
            FROM transaksi_keluar
            GROUP BY bahan_baku_id
        ) keluar'), 'keluar.bahan_baku_id', '=', 'bb.bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        $monthlyOutgoing = DB::table('transaksi_keluar as tk')
            ->select(
                'tk.bahan_baku_id',
                DB::raw('DATE_FORMAT(tk.tanggal_transaksi, "%Y-%m") as bulan'),
                DB::raw('SUM(tk.jumlah) as jumlah_keluar'),
            )
            ->whereBetween('tk.tanggal_transaksi', [
                $startDate->copy()->startOfMonth()->subMonths($numberOfMonths),
                $startDate->copy()->endOfMonth()
            ])
            ->groupBy('bahan_baku_id', 'bulan')
            ->get()
            ->groupBy('bahan_baku_id');

        $monthlyIngoing = DB::table('transaksi_masuk as tm')
            ->select(
                'tm.bahan_baku_id',
                DB::raw('DATE_FORMAT(tm.tanggal_transaksi, "%Y-%m") as bulan'),
                DB::raw('SUM(tm.jumlah) as jumlah_masuk')
            )
            ->whereBetween('tm.tanggal_transaksi', [
                $startDate->copy()->startOfMonth()->subMonths($numberOfMonths),
                $startDate->copy()->endOfMonth()
            ])
            ->groupBy('bahan_baku_id', 'bulan')
            ->get()
            ->groupBy('bahan_baku_id');

        $latestSuppliers = DB::table('transaksi_masuk as tm')
            ->join('supplier as s', 's.supplier_id', '=', 'tm.supplier_id')
            ->select(
                'tm.bahan_baku_id',
                'tm.supplier_id',
                's.nama_supplier',
                'tm.harga_per_satuan',
                'tm.tanggal_transaksi'
            )
            ->whereNotNull('tm.supplier_id')
            ->whereNotNull('tm.harga_per_satuan')
            ->whereIn(DB::raw('(tm.bahan_baku_id, tm.tanggal_transaksi)'), function ($query) {
                $query->select('bahan_baku_id', DB::raw('MAX(tanggal_transaksi)'))
                    ->from('transaksi_masuk')
                    ->whereNotNull('supplier_id')
                    ->groupBy('bahan_baku_id');
            })
            ->get()
            ->keyBy('bahan_baku_id');

        return $bahanBaku->map(function ($item) use ($dates, $currentStocks, $monthlyOutgoing, $monthlyIngoing, $latestSuppliers, $startDate) {
            $itemMonthlyData = $dates->map(function ($date) use ($monthlyOutgoing, $monthlyIngoing, $item) {
                $monthKey = $date->format('Y-m');

                $monthOutgoingData = $monthlyOutgoing->get($item->bahan_baku_id, collect())
                    ->firstWhere('bulan', $monthKey);

                $monthIngoingData = $monthlyIngoing->get($item->bahan_baku_id, collect())
                    ->firstWhere('bulan', $monthKey);

                return [
                    'bulan' => self::formatBulan($monthKey),
                    'jumlah_keluar' => $monthOutgoingData ? $monthOutgoingData->jumlah_keluar : 0,
                    'jumlah_masuk' => $monthIngoingData ? $monthIngoingData->jumlah_masuk : 0,
                ];
            });

            $lastThreeMonths = $itemMonthlyData->slice(-3);
            $totalKeluar = $lastThreeMonths->sum('jumlah_keluar');
            $totalMasuk = $lastThreeMonths->sum('jumlah_masuk');

            $averageKeluar = $lastThreeMonths->count() > 0 ? $totalKeluar / $lastThreeMonths->count() : 0;
            $averageMasuk = $lastThreeMonths->count() > 0 ? $totalMasuk / $lastThreeMonths->count() : 0;

            $currentStock = intval($currentStocks->get($item->bahan_baku_id)->total_stok ?? 0);

            if ($currentStock < $item->stok_minimal) {
                $prediksiKeluar = round($averageKeluar - $currentStock + $item->stok_minimal);
                $prediksiMasuk = round($averageMasuk - $currentStock + $item->stok_minimal);
            } else {
                $prediksiKeluar = round($averageKeluar);
                $prediksiMasuk = round($averageMasuk);
            }

            $prediksiData = [
                'bulan' => self::formatBulan($startDate),
                'jumlah_keluar' => $prediksiKeluar,
                'jumlah_masuk' => $prediksiMasuk,
            ];

            $latestSupplier = $latestSuppliers->get($item->bahan_baku_id);

            return [
                'bahan_baku_id' => $item->bahan_baku_id,
                'kode_bahan_baku' => $item->kode_bahan_baku,
                'nama_bahan_baku' => $item->nama_bahan_baku,
                'satuan' => $item->satuan,
                'stok_minimal' => $item->stok_minimal,
                'stok_saat_ini' => $currentStock,
                'supplier_terakhir' => $latestSupplier ? [
                    'supplier_id' => $latestSupplier->supplier_id,
                    'nama_supplier' => $latestSupplier->nama_supplier,
                    'harga_per_satuan' => $latestSupplier->harga_per_satuan,
                ] : null,
                'data_bulanan' => $itemMonthlyData,
                'prediksi_bulan_selanjutnya' => $prediksiData,
            ];
        });
    }
}
