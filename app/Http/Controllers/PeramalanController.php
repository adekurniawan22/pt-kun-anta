<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, BahanBakuTransaksi, Supplier};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PeramalanController extends Controller
{
    private const TITLE_INDEX = 'Peramalan Bahan Baku';

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

        $pdf = PDF::loadView('menu.peramalan.pdf', [
            'bahan_baku' => $bahan_baku,
            'title' => 'Peramalan Bahan Baku',
            'bulan_prediksi' => $date_prediksi->locale('id')->monthName . ' ' . $date_prediksi->year
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('peramalan_' . now()->format('Y-m-d') . '.pdf');
    }
}
