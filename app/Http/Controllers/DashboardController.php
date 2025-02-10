<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, BahanBaku, TransaksiMasuk, Pengguna, TransaksiKeluar};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function manajer_produksi()
    {
        // Get top materials with their suppliers and total value
        $topBahanBaku = TransaksiMasuk::query()
            ->select(
                'bahan_baku_id',
                DB::raw('count(*) as total_pembelian'),
                DB::raw('GROUP_CONCAT(DISTINCT supplier_id) as supplier_ids'),
                DB::raw('SUM(jumlah * harga_per_satuan) as total_nilai_pembelian')
            )
            ->with([
                'bahanBaku:bahan_baku_id,nama_bahan_baku',
                'supplier:supplier_id,nama_supplier'
            ])
            ->whereNotNull('harga_per_satuan')
            ->groupBy('bahan_baku_id')
            ->orderBy('total_nilai_pembelian', 'desc')
            ->take(5)
            ->get();

        // Get top suppliers with transaction count and total value
        $topSupplier = TransaksiMasuk::query()
            ->select(
                'supplier_id',
                DB::raw('count(*) as total_transaksi'),
                DB::raw('SUM(jumlah * harga_per_satuan) as total_nilai_transaksi')
            )
            ->whereNotNull('supplier_id')
            ->whereNotNull('harga_per_satuan')
            ->with('supplier:supplier_id,nama_supplier')
            ->groupBy('supplier_id')
            ->orderBy('total_nilai_transaksi', 'desc')
            ->take(5)
            ->get();

        // Get all materials
        $bahanBaku = BahanBaku::all();

        // Calculate current stock considering both incoming and outgoing transactions
        $currentStocks = DB::query()
            ->fromSub(function ($query) {
                $query->from('transaksi_masuk')
                    ->select('bahan_baku_id')
                    ->selectRaw('COALESCE(SUM(jumlah), 0) as total_masuk')
                    ->groupBy('bahan_baku_id')
                    ->union(
                        DB::table('transaksi_keluar')
                            ->select('bahan_baku_id')
                            ->selectRaw('COALESCE(SUM(jumlah), 0) as total_masuk')
                            ->groupBy('bahan_baku_id')
                    );
            }, 'combined_transactions')
            ->select('bahan_baku_id')
            ->selectRaw('SUM(CASE 
            WHEN total_masuk IS NOT NULL THEN total_masuk 
            ELSE -total_masuk 
        END) as total_stok')
            ->groupBy('bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        // Process materials with their current stock status
        $bahanBakuFiltered = $bahanBaku->map(function ($item) use ($currentStocks) {
            $stockRecord = $currentStocks->get($item->bahan_baku_id);
            $currentStock = $stockRecord ? $stockRecord->total_stok : 0;

            // Calculate stock status
            $statusStok = 'Stok Aman';
            if ($currentStock <= $item->stok_minimal) {
                $statusStok = 'Stok Rendah';
            } elseif ($currentStock <= ($item->stok_minimal * 1.3)) {
                $statusStok = 'Stok Kritis';
            }

            return [
                'nama_bahan_baku' => $item->nama_bahan_baku,
                'satuan' => $item->satuan,
                'stok_minimal' => $item->stok_minimal,
                'stok_saat_ini' => $currentStock,
                'status_stok' => $statusStok,
            ];
        });

        // Filter materials by stock status
        $stokRendah = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Rendah';
        });

        $stokKritis = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Kritis';
        });

        // Get current month's transaction counts
        $currentMonth = Carbon::now();
        $transactionCounts = $this->getMonthlyTransactionCounts($currentMonth);

        return view('menu.dashboard.manajer_produksi', [
            'title' => 'Dashboard Manajer Produksi',
            'jumlahSupplier' => Supplier::count(),
            'jumlahBahanBaku' => BahanBaku::count(),
            'jumlahTransaksiMasukBulanIni' => $transactionCounts['masuk'],
            'jumlahTransaksiKeluarBulanIni' => $transactionCounts['keluar'],
            'topBahanBaku' => $topBahanBaku,
            'topSupplier' => $topSupplier,
            'stokRendah' => $stokRendah,
            'stokKritis' => $stokKritis,
        ]);
    }

    public function supervisor()
    {
        // Get current month's transaction statistics
        $currentMonth = Carbon::now();
        $currentMonth = Carbon::now();
        $transactionCounts = $this->getMonthlyTransactionCounts($currentMonth);

        return view('menu.dashboard.supervisor', [
            'title' => 'Dashboard Supervisor',
            'jumlahTransaksiMasukBulanIni' => $transactionCounts['masuk'],
            'jumlahTransaksiKeluarBulanIni' => $transactionCounts['keluar'],
        ]);
    }

    public function admin()
    {
        // Get top materials with their suppliers
        $topBahanBaku = TransaksiMasuk::query()
            ->select(
                'bahan_baku_id',
                DB::raw('count(*) as total_pembelian'),
                DB::raw('GROUP_CONCAT(DISTINCT supplier_id) as supplier_ids'),
                DB::raw('SUM(jumlah * harga_per_satuan) as total_nilai_pembelian')
            )
            ->with([
                'bahanBaku:bahan_baku_id,nama_bahan_baku',
                'supplier:supplier_id,nama_supplier'
            ])
            ->whereNotNull('harga_per_satuan')
            ->groupBy('bahan_baku_id')
            ->orderBy('total_nilai_pembelian', 'desc')
            ->take(5)
            ->get();

        // Get top suppliers with transaction count and total value
        $topSupplier = TransaksiMasuk::query()
            ->select(
                'supplier_id',
                DB::raw('count(*) as total_transaksi'),
                DB::raw('SUM(jumlah * harga_per_satuan) as total_nilai_transaksi')
            )
            ->whereNotNull('supplier_id')
            ->whereNotNull('harga_per_satuan')
            ->with('supplier:supplier_id,nama_supplier')
            ->groupBy('supplier_id')
            ->orderBy('total_nilai_transaksi', 'desc')
            ->take(5)
            ->get();

        // Get all materials
        $bahanBaku = BahanBaku::all();

        // Calculate stocks more accurately
        $stokMasuk = DB::table('transaksi_masuk')
            ->select('bahan_baku_id')
            ->selectRaw('COALESCE(SUM(jumlah), 0) as total_masuk')
            ->groupBy('bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        $stokKeluar = DB::table('transaksi_keluar')
            ->select('bahan_baku_id')
            ->selectRaw('COALESCE(SUM(jumlah), 0) as total_keluar')
            ->groupBy('bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        // Process materials with their current stock status
        $bahanBakuFiltered = $bahanBaku->map(function ($item) use ($stokMasuk, $stokKeluar) {
            $totalMasuk = $stokMasuk->get($item->bahan_baku_id);
            $totalKeluar = $stokKeluar->get($item->bahan_baku_id);

            $currentStock = ($totalMasuk ? $totalMasuk->total_masuk : 0) -
                ($totalKeluar ? $totalKeluar->total_keluar : 0);

            // Calculate stock status
            $statusStok = 'Stok Aman';
            if ($currentStock <= $item->stok_minimal) {
                $statusStok = 'Stok Rendah';
            } elseif ($currentStock <= ($item->stok_minimal * 1.3)) {
                $statusStok = 'Stok Kritis';
            }

            return [
                'nama_bahan_baku' => $item->nama_bahan_baku,
                'satuan' => $item->satuan,
                'stok_minimal' => $item->stok_minimal,
                'stok_saat_ini' => $currentStock,
                'status_stok' => $statusStok,
            ];
        });

        // Filter materials by stock status using traditional function syntax
        $stokRendah = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Rendah';
        });

        $stokKritis = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Kritis';
        });

        // Get current month's transaction counts
        $currentMonth = Carbon::now();
        $transactionCounts = $this->getMonthlyTransactionCounts($currentMonth);

        return view('menu.dashboard.admin', [
            'title' => 'Dashboard Admin',
            'jumlahPengguna' => Pengguna::count(),
            'jumlahSupplier' => Supplier::count(),
            'jumlahBahanBaku' => BahanBaku::count(),
            'jumlahTransaksiMasukBulanIni' => $transactionCounts['masuk'],
            'jumlahTransaksiKeluarBulanIni' => $transactionCounts['keluar'],
            'topBahanBaku' => $topBahanBaku,
            'topSupplier' => $topSupplier,
            'stokRendah' => $stokRendah,
            'stokKritis' => $stokKritis,
        ]);
    }

    private function getMonthlyTransactionCounts(Carbon $date)
    {
        return [
            'masuk' => TransaksiMasuk::query()
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->count(),
            'keluar' => TransaksiKeluar::query()
                ->whereMonth('tanggal_transaksi', $date->month)
                ->whereYear('tanggal_transaksi', $date->year)
                ->count(),
        ];
    }
}
