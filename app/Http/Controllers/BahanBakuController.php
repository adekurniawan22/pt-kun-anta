<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, BahanBakuTransaksi, Supplier};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BahanBakuController extends Controller
{
    private const TITLE_INDEX = 'Daftar Bahan Baku';
    private const TITLE_CREATE = 'Tambah Bahan Baku';
    private const TITLE_EDIT = 'Edit Bahan Baku';

    public function index()
    {
        $bahan_baku = BahanBaku::withSum(['transaksi as total_masuk' => function ($query) {
            $query->where('tipe', 'masuk');
        }, 'transaksi as total_keluar' => function ($query) {
            $query->where('tipe', 'keluar');
        }], 'jumlah')
            ->get()
            ->map(function ($item) {
                $item->stok = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
                return $item;
            });

        return view('menu.bahan_baku.index', [
            'bahan_baku' => $bahan_baku,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        $lastBahanBaku = BahanBaku::orderBy('bahan_baku_id', 'desc')->first();
        $lastId = $lastBahanBaku ? $lastBahanBaku->bahan_baku_id + 1 : 1;

        return view('menu.bahan_baku.create', [
            'title' => self::TITLE_CREATE,
            'lastId' => $lastId
        ]);
    }

    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        BahanBaku::create([
            'kode_bahan_baku' => $request->kode_bahan_baku,
            'nama_bahan_baku' => $request->nama_bahan_baku,
            'satuan' => $request->satuan,
            'stok_minimal' => $request->stok_minimal,
            'dibuat_oleh' => session()->get('pengguna_id')
        ]);

        return redirect()->route(session()->get('role') . '.bahan_baku.index')->with('success', 'Bahan Baku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $bahanBaku = BahanBaku::findOrFail($id);
        return view('menu.bahan_baku.edit', [
            'bahanBaku' => $bahanBaku,
            'title' => self::TITLE_EDIT,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $bahan_baku = BahanBaku::findOrFail($id);

        $bahan_baku->kode_bahan_baku = $request->kode_bahan_baku;
        $bahan_baku->nama_bahan_baku = $request->nama_bahan_baku;
        $bahan_baku->satuan = $request->satuan;
        $bahan_baku->stok_minimal = $request->stok_minimal;

        // Cek apakah ada perubahan
        if ($bahan_baku->isDirty()) {
            $bahan_baku->save();
            return redirect()->route(session()->get('role') . '.bahan_baku.index')->with('success', 'Bahan Baku berhasil diedit.');
        }

        return redirect()->route(session()->get('role') . '.bahan_baku.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    public function destroy($id)
    {
        try {
            BahanBaku::findOrFail($id)->delete();
            return redirect()->route(session()->get('role') . '.bahan_baku.index')
                ->with('success', 'Bahan Baku berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route(session()->get('role') . '.bahan_baku.index')
                ->with('error', 'Bahan baku ini tidak dapat dihapus karena masih terdapat data bahan baku yang terkait dalam transaksi.');
        }
    }

    public function getDataMonthYear(Request $request)
    {
        $bahanBakuId = $request->input('bahan_baku_id');
        $range_bulan = $request->input('range_bulan') ? (int) $request->input('range_bulan') : 6;
        $bulanTerakhir = Carbon::now()->subMonths($range_bulan)->startOfMonth();
        $namaBulan = [
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

        $bulanRange = collect();
        $bulanTahunIndonesia = [];

        for ($i = 0; $i < $range_bulan; $i++) {
            // $bulanSaatIni = Carbon::now()->subMonths($range_bulan - $i + 2);
            $bulanSaatIni = Carbon::now()->subMonths($range_bulan - $i);
            $bulanRange->push($bulanSaatIni->format('Y-m'));
            $bulanTahunIndonesia[] = $namaBulan[$bulanSaatIni->month] . ' ' . $bulanSaatIni->year;
        }

        // Fetch the transactions for the given bahan_baku_id
        $dataKeluar = BahanBakuTransaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('YEAR(tanggal_transaksi) as tahun'),
            'bahan_baku_id',
            DB::raw('SUM(jumlah) as total_keluar')
        )
            ->where('tipe', 'keluar')
            ->where('tanggal_transaksi', '>=', $bulanTerakhir)
            ->where('bahan_baku_id', $bahanBakuId)
            ->groupBy(DB::raw('MONTH(tanggal_transaksi)'), DB::raw('YEAR(tanggal_transaksi)'), 'bahan_baku_id')
            ->orderBy(DB::raw('YEAR(tanggal_transaksi)'), 'desc')
            ->orderBy(DB::raw('MONTH(tanggal_transaksi)'), 'desc')
            ->get();

        $dataMasuk = BahanBakuTransaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('YEAR(tanggal_transaksi) as tahun'),
            'bahan_baku_id',
            DB::raw('SUM(jumlah) as total_masuk')
        )
            ->where('tipe', 'masuk')
            ->where('tanggal_transaksi', '>=', $bulanTerakhir)
            ->where('bahan_baku_id', $bahanBakuId)
            ->groupBy(DB::raw('MONTH(tanggal_transaksi)'), DB::raw('YEAR(tanggal_transaksi)'), 'bahan_baku_id')
            ->orderBy(DB::raw('YEAR(tanggal_transaksi)'), 'desc')
            ->orderBy(DB::raw('MONTH(tanggal_transaksi)'), 'desc')
            ->get();

        // Find the bahan baku first
        $bahanBaku = BahanBaku::findOrFail($bahanBakuId);

        // Initialize final data with zeros
        $bulanDataKeluar = array_fill(0, $range_bulan, 0);
        $bulanDataMasuk = array_fill(0, $range_bulan, 0);
        $namaDetailBulan = $bulanTahunIndonesia;

        // If there are transactions, update the data
        if ($dataKeluar->count() > 0) {
            foreach ($bulanRange as $index => $bulan) {
                $bulanTahun = Carbon::parse($bulan);
                $bulanTransaksiKeluar = $dataKeluar->firstWhere(function ($item) use ($bulanTahun) {
                    return $item->bulan == $bulanTahun->month && $item->tahun == $bulanTahun->year;
                });

                $bulanDataKeluar[$index] = $bulanTransaksiKeluar ? $bulanTransaksiKeluar->total_keluar : 0;
            }
        }

        if ($dataMasuk->count() > 0) {
            foreach ($bulanRange as $index => $bulan) {
                $bulanTahun = Carbon::parse($bulan);
                $bulanTransaksiMasuk = $dataMasuk->firstWhere(function ($item) use ($bulanTahun) {
                    return $item->bulan == $bulanTahun->month && $item->tahun == $bulanTahun->year;
                });

                $bulanDataMasuk[$index] = $bulanTransaksiMasuk ? $bulanTransaksiMasuk->total_masuk : 0;
            }
        }

        // Create final data array
        $finalData[] = [
            'bahan_baku' => $bahanBaku,
            'data_keluar' => $bulanDataKeluar,
            'data_masuk' => $bulanDataMasuk,
            'nama_bulan' => $namaDetailBulan,
        ];

        // Return the final data as a JSON response
        return response()->json($finalData);
    }

    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'kode_bahan_baku' => 'required|string|max:40',
            'nama_bahan_baku' => 'required|string|max:100',
            'satuan' => 'required|string|max:100',
            'stok_minimal' => 'required|integer|min:1',
        ];

        $customAttributes = [
            'kode_bahan_baku' => 'Kode Bahan Baku',
            'nama_bahan_baku' => 'Nama Bahan Baku',
            'satuan' => 'Satuan',
            'stok_minimal' => 'Stok Minimum',
        ];

        return $request->validate($rules, [], $customAttributes);
    }

    public function generatePDF()
    {
        $data = $this->getBahanBakuData();
        $pdf = PDF::loadView('menu.bahan_baku.pdf', [
            'data' => $data,
            'title' => 'Laporan Bahan Baku'
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_bahan_baku_' . now()->format('Y-m-d') . '.pdf');
    }

    private function getDataCalculateSMA($range_bulan = 3)
    {
        $bulanTerakhir = Carbon::now()->subMonths($range_bulan)->startOfMonth();

        $namaBulan = [
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

        $bulanRange = collect();
        $bulanTahunIndonesia = [];

        for ($i = 0; $i < $range_bulan; $i++) {
            $bulanSaatIni = Carbon::now()->subMonths($range_bulan - $i);
            $bulanRange->push($bulanSaatIni->format('Y-m'));
            $bulanTahunIndonesia[] = $namaBulan[$bulanSaatIni->month] . ' ' . $bulanSaatIni->year;
        }

        $data = BahanBaku::withSum(['transaksi as total_masuk' => function ($query) {
            $query->where('tipe', 'masuk');
        }], 'jumlah')
            ->withSum(['transaksi as total_keluar' => function ($query) {
                $query->where('tipe', 'keluar');
            }], 'jumlah')
            ->get()
            ->map(function ($item) use ($bulanRange, $bulanTahunIndonesia, $bulanTerakhir) {
                $item->stok = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
                $dataKeluar = BahanBakuTransaksi::select(
                    DB::raw('MONTH(tanggal_transaksi) as bulan'),
                    DB::raw('YEAR(tanggal_transaksi) as tahun'),
                    DB::raw('SUM(jumlah) as total_keluar')
                )
                    ->where('tipe', 'keluar')
                    ->where('tanggal_transaksi', '>=', $bulanTerakhir)
                    ->where('bahan_baku_id', $item->bahan_baku_id)
                    ->groupBy(DB::raw('MONTH(tanggal_transaksi)'), DB::raw('YEAR(tanggal_transaksi)'))
                    ->orderBy(DB::raw('YEAR(tanggal_transaksi)'), 'desc')
                    ->orderBy(DB::raw('MONTH(tanggal_transaksi)'), 'desc')
                    ->get();

                $bulanDataKeluar = array_fill(0, count($bulanRange), 0);

                if ($dataKeluar->count() > 0) {
                    foreach ($bulanRange as $index => $bulan) {
                        $bulanTahun = Carbon::parse($bulan);
                        $bulanTransaksiKeluar = $dataKeluar->firstWhere(function ($data) use ($bulanTahun) {
                            return $data->bulan == $bulanTahun->month && $data->tahun == $bulanTahun->year;
                        });

                        $bulanDataKeluar[$index] = $bulanTransaksiKeluar ? $bulanTransaksiKeluar->total_keluar : 0;
                    }
                }

                $item->rata_rata = count($bulanDataKeluar) > 0
                    ? round(array_sum($bulanDataKeluar) / count($bulanDataKeluar), 0)
                    : 0;

                $transaksiMasukTerakhir = BahanBakuTransaksi::where('bahan_baku_id', $item->bahan_baku_id)
                    ->where('tipe', 'masuk')
                    ->orderBy('bahan_baku_transaksi_id', 'desc')
                    ->first();

                if ($transaksiMasukTerakhir && $transaksiMasukTerakhir->supplier_id) {
                    $supplier = Supplier::find($transaksiMasukTerakhir->supplier_id);
                    $item->supplier_terakhir = $supplier ? $supplier->nama_supplier : 'Tidak diketahui';
                } else {
                    $item->supplier_terakhir = 'Tidak diketahui';
                }

                $item->data_keluar_bulanan = $bulanDataKeluar;
                $item->nama_bulan = $bulanTahunIndonesia;

                return $item;
            });

        return $data;
    }
}
