<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, Supplier, BahanBakuTransaksi, Pengguna, TransaksiKeluar, TransaksiMasuk};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BahanBakuTransaksiController extends Controller
{
    private const TITLE_INDEX = 'Daftar Penggunaan Bahan Baku';
    private const TITLE_CREATE = 'Tambah Penggunaan Bahan Baku';
    private const TITLE_EDIT = 'Edit Penggunaan Bahan Baku';

    public function index()
    {
        $data = DB::table('transaksi_masuk')
            ->select(
                'transaksi_masuk_id as id',
                'bahan_baku_id',
                'tanggal_transaksi',
                'jumlah',
                'harga_per_satuan',
                'keterangan',
                'supplier_id',
                'dibuat_oleh',
                DB::raw("'masuk' as tipe")
            )
            ->union(
                DB::table('transaksi_keluar')
                    ->select(
                        'transaksi_keluar_id as id',
                        'bahan_baku_id',
                        'tanggal_transaksi',
                        'jumlah',
                        DB::raw('NULL as harga_per_satuan'),
                        'keterangan',
                        DB::raw('NULL as supplier_id'),
                        'dibuat_oleh',
                        DB::raw("'keluar' as tipe")
                    )
            )
            ->orderBy('tanggal_transaksi', 'desc')
            ->get()
            ->map(function ($item) {
                $item->bahanBaku = BahanBaku::find($item->bahan_baku_id);
                $item->supplier = $item->supplier_id ? Supplier::find($item->supplier_id) : null;
                $item->pengguna = $item->dibuat_oleh ? Pengguna::find($item->dibuat_oleh) : null;
                return $item;
            });

        return view('menu.transaksi.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        $bahanBaku = BahanBaku::all();
        $suppliers = Supplier::all()->map(function ($supplier) {
            return [
                'supplier_id' => $supplier->supplier_id,
                'nama_supplier' => $supplier->nama_supplier,
                'bahan_baku' => $supplier->bahan_baku
            ];
        });

        return view('menu.transaksi.create', [
            'title' => self::TITLE_CREATE,
            'bahanBaku' => $bahanBaku,
            'suppliers' => $suppliers
        ]);
    }

    public function store(Request $request)
    {
        foreach ($request->bahan_baku_id as $index => $bahanBakuId) {
            $supplier_id = $request->supplier_id[$index] != null ? $request->supplier_id[$index] : null;

            $datGabungan = [];
            if ($request->tipe[$index] === "keluar") {
                $data = [
                    'tipe' => $request->tipe[$index],
                    'bahan_baku_id' => $bahanBakuId,
                    'tanggal_transaksi' => $request->tanggal_transaksi[$index],
                    'jumlah' => $request->jumlah[$index],
                    'keterangan' => $request->keterangan[$index],
                    'dibuat_oleh' => session()->get('pengguna_id'),
                ];
                $datGabungan[] = $data;
                TransaksiKeluar::create($data);
            } else {
                $data = [
                    'tipe' => $request->tipe[$index],
                    'bahan_baku_id' => $bahanBakuId,
                    'supplier_id' => $supplier_id,
                    'tanggal_transaksi' => $request->tanggal_transaksi[$index],
                    'jumlah' => $request->jumlah[$index],
                    'harga_per_satuan' => $request->harga[$index],
                    'keterangan' => $request->keterangan[$index],
                    'dibuat_oleh' => session()->get('pengguna_id'),
                ];
                $datGabungan[] = $data;
                TransaksiMasuk::create($data);
            }
        }

        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil ditambahkan.');
    }

    public function edit($tipe, $id)
    {
        if ($tipe === 'masuk') {
            $bahanBakuTransaksi = TransaksiMasuk::findOrFail($id);
        } else {
            $bahanBakuTransaksi = TransaksiKeluar::findOrFail($id);
        }

        $bahanBaku = BahanBaku::all();
        $suppliers = Supplier::all();

        return view('menu.transaksi.edit', [
            'bahanBakuTransaksi' => $bahanBakuTransaksi,
            'title' => self::TITLE_EDIT,
            'bahanBaku' => $bahanBaku,
            'suppliers' => $suppliers,
            'tipe' => $tipe, // Menyertakan tipe transaksi ke view
        ]);
    }


    public function update(Request $request, $tipe, $id)
    {
        // Validasi data
        $this->validateStoreOrUpdate($request, $id);

        // Pilih model berdasarkan tipe transaksi
        $historyBahanBaku = match ($tipe) {
            'masuk' => TransaksiMasuk::findOrFail($id),
            'keluar' => TransaksiKeluar::findOrFail($id),
            default => abort(404, 'Tipe transaksi tidak valid')
        };

        // Tentukan nilai yang akan diperbarui
        $data = [
            'bahan_baku_id' => $request->bahan_baku_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ];

        // Tambahkan field tambahan jika transaksi masuk
        if ($tipe === 'masuk') {
            $data['supplier_id'] = $request->supplier_id;
            $data['harga_per_satuan'] = $request->harga_per_satuan;
        }

        // Update transaksi
        $historyBahanBaku->update($data);

        // Redirect dengan pesan sukses
        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil diperbarui.');
    }


    public function destroy($tipe, $id)
    {
        // dd($tipe, $id);
        if ($tipe === 'masuk') {
            $historyBahanBaku = TransaksiMasuk::findOrFail($id);
            $historyBahanBaku->forceDelete();
        } else {
            $historyBahanBaku = TransaksiKeluar::findOrFail($id);
            $historyBahanBaku->forceDelete();
        }

        // Redirect dengan pesan sukses
        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil dihapus.');
    }


    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        // FARHAN
        $rules = [
            'bahan_baku_id' => 'required|exists:bahan_baku,bahan_baku_id',
            'tipe' => 'required|in:masuk,keluar',
            'supplier_id' => 'required_if:tipe,masuk',
            'tanggal_transaksi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_per_satuan' => 'required_if:tipe,masuk|nullable|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ];

        $customAttributes = [
            'bahan_baku_id' => 'Bahan Baku',
            'tipe' => 'Tipe',
            'supplier_id' => 'Supplier',
            'tanggal_transaksi' => 'Tanggal Transaksi',
            'jumlah' => 'Jumlah Bahan Baku',
            'harga_per_satuan' => 'Harga per satuan bahan baku',
            'keterangan' => 'Keterangan',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}
