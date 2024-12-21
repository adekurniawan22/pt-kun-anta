<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, BahanBaku, BahanBakuTransaksi, Pengguna};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function manajer_produksi()
    {
        return view('menu.dashboard.manajer_produksi', [
            'title' => 'Manajer Produksi Dashboard',
        ]);
    }

    public function supervisor()
    {
        return view('menu.dashboard.supervisor', [
            'title' => 'Supervisor Dashboard',
        ]);
    }

    public function admin()
    {

        return view('menu.dashboard.admin', [
            'title' => 'Admin Dashboard',
            'jumlahPengguna' => Pengguna::where('role', '!=', 'admin')->count(),
            'jumlahSupplier' => Supplier::count(),
            'jumlahBahanBaku' => BahanBaku::count(),
            'jumlahTransaksiMasukBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'masuk')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month) // Filter berdasarkan bulan saat ini
                ->whereYear('tanggal_transaksi', Carbon::now()->year)   // Filter berdasarkan tahun saat ini
                ->count(),
            'jumlahTransaksiKeluarBulanIni' => BahanBakuTransaksi::where('tipe', '=', 'keluar')
                ->whereMonth('tanggal_transaksi', Carbon::now()->month) // Filter berdasarkan bulan saat ini
                ->whereYear('tanggal_transaksi', Carbon::now()->year)   // Filter berdasarkan tahun saat ini
                ->count(),
        ]);
    }

    public function getChartData(Request $request)
    {
        // Get the year from the request, default to current year if not provided
        $year = $request->input('year', Carbon::now()->year);

        // Get all completed orders for the specified year with their details
        $orders = Pesanan::with('pesananDetails.produk')
            ->where('status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->get();

        // Calculate monthly revenues
        $monthlyRevenues = collect(range(1, 12))->map(function ($month) use ($orders, $year) {
            // Filter orders for this specific month
            $monthOrders = $orders->filter(function ($order) use ($month) {
                return Carbon::parse($order->tanggal_pesanan)->month === $month;
            });

            // Calculate total revenue for the month
            $totalRevenue = $monthOrders->flatMap(function ($order) {
                return $order->pesananDetails->map(function ($detail) {
                    return $detail->jumlah * $detail->produk->harga;
                });
            })->sum();

            return [
                'month' => $month,
                'month_name' => $this->indonesianMonths[$month],
                'total_revenue' => $totalRevenue
            ];
        });

        // Calculate top selling products
        $topProducts = $orders->flatMap(function ($order) {
            return $order->pesananDetails;
        })
            ->groupBy('produk_id')
            ->map(function ($details) {
                $product = $details->first()->produk;
                return [
                    'name' => $product->nama_produk,
                    'total_sold' => $details->sum('jumlah')
                ];
            })
            ->sortByDesc('total_sold')
            ->take(5)
            ->values();

        // Prepare the response data
        $responseData = [
            'year' => $year,
            'monthly_revenues' => $monthlyRevenues,
            'total_annual_revenue' => $monthlyRevenues->sum('total_revenue'),
            'top_products' => $topProducts
        ];

        return response()->json($responseData);
    }

    public function generatePdfReport(Request $request, $year = null)
    {
        // Jika $year null, gunakan tahun saat ini
        $year = (int)$year ?? Carbon::now()->year;

        // Get all completed orders for the specified year with their details
        $orders = Pesanan::with('pesananDetails.produk')
            ->where('status', 'selesai')
            ->whereYear('tanggal_pesanan', $year)
            ->get();

        // Calculate monthly revenues
        $monthlyRevenues = collect(range(1, 12))->map(function ($month) use ($orders, $year) {
            // Filter orders for this specific month
            $monthOrders = $orders->filter(function ($order) use ($month) {
                return Carbon::parse($order->tanggal_pesanan)->month === $month;
            });

            // Calculate total revenue for the month
            $totalRevenue = $monthOrders->flatMap(function ($order) {
                return $order->pesananDetails->map(function ($detail) {
                    return $detail->jumlah * $detail->produk->harga;
                });
            })->sum();

            return [
                'month' => $month,
                'month_name' => $this->indonesianMonths[$month],
                'total_revenue' => $totalRevenue
            ];
        });

        // Calculate top selling products
        $topProducts = $orders->flatMap(function ($order) {
            return $order->pesananDetails;
        })
            ->groupBy('produk_id')
            ->map(function ($details) {
                $product = $details->first()->produk;
                return [
                    'name' => $product->nama_produk,
                    'total_sold' => $details->sum('jumlah')
                ];
            })
            ->sortByDesc('total_sold')
            ->take(5)
            ->values();

        // Prepare the report data
        $reportData = [
            'year' => $year,
            'monthly_revenues' => $monthlyRevenues,
            'total_annual_revenue' => $monthlyRevenues->sum('total_revenue'),
            'top_products' => $topProducts,
        ];

        // Define the file name
        $fileName = 'laporan_penjualan_' . $year . '_' . now()->format('Y-m-d') . '.pdf';

        // Generate the PDF
        $pdf = PDF::loadView('menu.dashboard.keuangan_pdf', [
            'year' => $year,
            'reportData' => $reportData,
        ]);

        // Stream the PDF to the browser
        return $pdf->stream($fileName);
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
