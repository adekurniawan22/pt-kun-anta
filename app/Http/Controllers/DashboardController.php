<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, BahanBaku, BahanBakuTransaksi, Pengguna};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function manajer_produksi()
    {
        $topBahanBaku = BahanBakuTransaksi::where('tipe', 'masuk')
            ->select(
                'bahan_baku_id',
                DB::raw('count(*) as total_pembelian'),
                DB::raw('GROUP_CONCAT(DISTINCT supplier_id) as supplier_ids')
            )
            ->with([
                'bahanBaku:bahan_baku_id,nama_bahan_baku',
                'supplier:supplier_id,nama_supplier'
            ])
            ->groupBy('bahan_baku_id')
            ->orderBy('total_pembelian', 'desc')
            ->take(5)
            ->get();

        $topSupplier = BahanBakuTransaksi::where('tipe', 'masuk')
            ->select('supplier_id', DB::raw('count(*) as total_transaksi'))
            ->whereNotNull('supplier_id')
            ->with('supplier:supplier_id,nama_supplier')
            ->groupBy('supplier_id')
            ->orderBy('total_transaksi', 'desc')
            ->take(5)
            ->get();

        $bahanBaku = BahanBaku::all();

        $currentStocks = DB::table('bahan_baku_transaksi')
            ->select(
                'bahan_baku_id',
                DB::raw('COALESCE(SUM(CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END), 0) as total_stok')
            )
            ->groupBy('bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        $bahanBakuFiltered = $bahanBaku->map(function ($item) use ($currentStocks) {
            $currentStock = $currentStocks->get($item->bahan_baku_id)?->total_stok ?? 0;

            $statusStok = 'Stok Aman';
            if ($currentStock <= $item->stok_minimal) {
                $statusStok = 'Stok Rendah';
            } elseif ($currentStock <= $item->stok_minimal * 0.3 + $item->stok_minimal) {
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

        $stokRendah = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Rendah';
        });

        $stokKritis = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Kritis';
        });

        return view('menu.dashboard.manajer_produksi', [
            'title' => 'Dashboard Manajer Produksi',
            'jumlahSupplier' => Supplier::count(),
            'jumlahBahanBaku' => BahanBaku::count(),
            'jumlahTransaksiMasukBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'masuk')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
            'jumlahTransaksiKeluarBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'keluar')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
            'topBahanBaku' => $topBahanBaku,
            'topSupplier' => $topSupplier,
            'stokRendah' => $stokRendah,
            'stokKritis' => $stokKritis,
        ]);
    }

    public function supervisor()
    {
        return view('menu.dashboard.supervisor', [
            'title' => 'Dashboard Supervisor',
            'jumlahTransaksiMasukBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'masuk')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
            'jumlahTransaksiKeluarBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'keluar')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
        ]);
    }

    public function admin()
    {
        $topBahanBaku = BahanBakuTransaksi::where('tipe', 'masuk')
            ->select(
                'bahan_baku_id',
                DB::raw('count(*) as total_pembelian'),
                DB::raw('GROUP_CONCAT(DISTINCT supplier_id) as supplier_ids')
            )
            ->with([
                'bahanBaku:bahan_baku_id,nama_bahan_baku',
                'supplier:supplier_id,nama_supplier'
            ])
            ->groupBy('bahan_baku_id')
            ->orderBy('total_pembelian', 'desc')
            ->take(5)
            ->get();

        $topSupplier = BahanBakuTransaksi::where('tipe', 'masuk')
            ->select('supplier_id', DB::raw('count(*) as total_transaksi'))
            ->whereNotNull('supplier_id')
            ->with('supplier:supplier_id,nama_supplier')
            ->groupBy('supplier_id')
            ->orderBy('total_transaksi', 'desc')
            ->take(5)
            ->get();

        $bahanBaku = BahanBaku::all();

        $currentStocks = DB::table('bahan_baku_transaksi')
            ->select(
                'bahan_baku_id',
                DB::raw('COALESCE(SUM(CASE WHEN tipe = "masuk" THEN jumlah ELSE -jumlah END), 0) as total_stok')
            )
            ->groupBy('bahan_baku_id')
            ->get()
            ->keyBy('bahan_baku_id');

        $bahanBakuFiltered = $bahanBaku->map(function ($item) use ($currentStocks) {
            $currentStock = $currentStocks->get($item->bahan_baku_id)?->total_stok ?? 0;

            $statusStok = 'Stok Aman';
            if ($currentStock <= $item->stok_minimal) {
                $statusStok = 'Stok Rendah';
            } elseif ($currentStock <= $item->stok_minimal * 0.3 + $item->stok_minimal) {
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

        $stokRendah = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Rendah';
        });

        $stokKritis = $bahanBakuFiltered->filter(function ($item) {
            return $item['status_stok'] === 'Stok Kritis';
        });

        return view('menu.dashboard.admin', [
            'title' => 'Dashboard Admin',
            'jumlahPengguna' => Pengguna::count(),
            'jumlahSupplier' => Supplier::count(),
            'jumlahBahanBaku' => BahanBaku::count(),
            'jumlahTransaksiMasukBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'masuk')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
            'jumlahTransaksiKeluarBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'keluar')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->whereYear('tanggal_transaksi', Carbon::now()->year)
                ->count(),
            'topBahanBaku' => $topBahanBaku,
            'topSupplier' => $topSupplier,
            'stokRendah' => $stokRendah,
            'stokKritis' => $stokKritis,
        ]);
    }

    private $indonesianMonths = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
}
