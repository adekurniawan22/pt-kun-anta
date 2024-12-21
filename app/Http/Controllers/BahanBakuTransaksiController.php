<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, Supplier, BahanBakuTransaksi};
use Illuminate\Http\Request;

class BahanBakuTransaksiController extends Controller
{
    // Constants for view titles
    private const TITLE_INDEX = 'Daftar Transaksi Bahan Baku';
    private const TITLE_CREATE = 'Tambah Transaksi Bahan Baku';
    private const TITLE_EDIT = 'Edit Transaksi Bahan Baku';

    // Index method (show all history transactions)
    public function index()
    {
        $data = BahanBakuTransaksi::with('bahanBaku', 'supplier', 'pengguna')->get();
        // dd(BahanBakuTransaksi::with('bahanBaku', 'supplier')->toSql());

        return view('menu.transaksi.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    // Create method (show form for creating new history transaction)
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
        // dd($request->all());
        foreach ($request->bahan_baku_id as $index => $bahanBakuId) {
            $supplier_id = $request->supplier_id[$index] != null ? $request->supplier_id[$index] : null;

            if ($request->tipe[$index] === "keluar") {
                $total = null;
            } else {
                $total = $request->total[$index];
            }

            $data = [
                'bahan_baku_id' => $bahanBakuId,
                'tipe' => $request->tipe[$index],
                'supplier_id' => $supplier_id,
                'tanggal_transaksi' => $request->tanggal_transaksi[$index],
                'jumlah' => $request->jumlah[$index],
                'total' => $total,
                'keterangan' => $request->keterangan[$index],
                'dibuat_oleh' => session()->get('pengguna_id'),
            ];

            if ($request->tipe[$index] === "masuk") {
                $bahan_baku = BahanBaku::findOrFail($bahanBakuId);
                // Hitung harga per satuan
                $hargaPerSatuan = round($request->total[$index] / $request->jumlah[$index], 0);

                // Perbarui harga per satuan di tabel bahan_baku
                $bahan_baku->harga_per_satuan = $hargaPerSatuan;
                $bahan_baku->save();
            } else

                BahanBakuTransaksi::create($data);
        }

        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $bahanBakuTransaksi = BahanBakuTransaksi::findOrFail($id);
        $bahanBaku = BahanBaku::all();
        $suppliers = Supplier::all();
        return view('menu.transaksi.edit', [
            'bahanBakuTransaksi' => $bahanBakuTransaksi,
            'title' => self::TITLE_EDIT,
            'bahanBaku' => $bahanBaku,
            'suppliers' => $suppliers
        ]);
    }

    // Update method (update history transaction data in the database)
    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);
        $historyBahanBaku = BahanBakuTransaksi::findOrFail($id);
        $supplier_id = $request->tipe === "masuk" ? $request->supplier_id : null;
        $historyBahanBaku->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'tipe' => $request->tipe,
            'supplier_id' => $supplier_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil diedit.');
    }

    // Destroy method (delete history transaction)
    public function destroy($id)
    {
        $historyBahanBaku = BahanBakuTransaksi::findOrFail($id); // Ambil transaksi history berdasarkan ID
        $historyBahanBaku->delete(); // Hapus transaksi history
        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil dihapus.');
    }

    // Private method for validation (to avoid duplication of logic)
    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'bahan_baku_id' => 'required|exists:bahan_baku,bahan_baku_id',
            'tipe' => 'required|in:masuk,keluar',
            'supplier_id' => 'required_if:tipe,masuk',
            'tanggal_transaksi' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ];

        $customAttributes = [
            'bahan_baku_id' => 'Bahan Baku',
            'tipe' => 'Tipe',
            'supplier_id' => 'Supplier',
            'tanggal_transaksi' => 'Tanggal Transaksi',
            'jumlah' => 'Jumlah Bahan Baku',
            'keterangan' => 'Keterangan',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}
