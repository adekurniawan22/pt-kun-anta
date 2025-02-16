<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, BahanBakuTransaksi, Pengguna, Supplier};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PeramalanController extends Controller
{
    private const TITLE_INDEX = 'Penentuan Pembelian';

    public function index(Request $request)
    {
        $date_prediksi = $request->query('prediksi');
        $date_prediksi = $date_prediksi ? Carbon::createFromFormat('d/m/Y', $date_prediksi) : Carbon::now();

        $bahan_baku = BahanBaku::getAllDetailedStock(3, $date_prediksi);

        return view('menu.peramalan.index', [
            'bahan_baku' => $bahan_baku,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function generatePDF(Request $request)
    {
        $date_prediksi = $request->query('prediksi');
        $date_prediksi = $date_prediksi ? Carbon::createFromFormat('d/m/Y', $date_prediksi) : Carbon::now();

        $bahan_baku = BahanBaku::getAllDetailedStock(3, $date_prediksi);
        $bahan_baku = $bahan_baku->sortBy('stok_saat_ini');

        $bahanBakuFiltered = $bahan_baku->filter(function ($item) {
            return $item['stok_minimal'] >= $item['stok_saat_ini'];
        });

        $nama_pembuat = Pengguna::find(session()->get('pengguna_id'));

        $pdf = PDF::loadView('menu.peramalan.pdf', [
            'bahan_baku' => $bahanBakuFiltered,
            'title' => 'PERMALAN BAHAN BAKU',
            'bulan_prediksi' => $date_prediksi->locale('id')->monthName . ' ' . $date_prediksi->year,
            'nama_pembuat' => $nama_pembuat->nama,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('peramalan_' . now()->format('Y-m-d') . '.pdf');
    }
}
