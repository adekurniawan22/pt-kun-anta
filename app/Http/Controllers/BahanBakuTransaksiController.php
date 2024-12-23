<?php

namespace App\Http\Controllers;

use App\Models\{BahanBaku, Supplier, BahanBakuTransaksi};
use Illuminate\Http\Request;

class BahanBakuTransaksiController extends Controller
{
    private const TITLE_INDEX = 'Daftar Transaksi Bahan Baku';
    private const TITLE_CREATE = 'Tambah Transaksi Bahan Baku';
    private const TITLE_EDIT = 'Edit Transaksi Bahan Baku';

    public function index()
    {
        $data = BahanBakuTransaksi::with('bahanBaku', 'supplier', 'pengguna')->get();

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

            if ($request->tipe[$index] === "keluar") {
                $harga_per_satuan = null;
            } else {
                $harga_per_satuan = $request->harga[$index];
            }

            $data = [
                'bahan_baku_id' => $bahanBakuId,
                'tipe' => $request->tipe[$index],
                'supplier_id' => $supplier_id,
                'tanggal_transaksi' => $request->tanggal_transaksi[$index],
                'jumlah' => $request->jumlah[$index],
                'harga_per_satuan' => $harga_per_satuan,
                'keterangan' => $request->keterangan[$index],
                'dibuat_oleh' => session()->get('pengguna_id'),
            ];

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

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);
        $historyBahanBaku = BahanBakuTransaksi::findOrFail($id);
        $supplier_id = $request->tipe === "masuk" ? $request->supplier_id : null;
        $harga_per_satuan = $request->tipe === "keluar" ? $request->harga_per_satuan : null;

        $historyBahanBaku->update([
            'bahan_baku_id' => $request->bahan_baku_id,
            'tipe' => $request->tipe,
            'supplier_id' => $supplier_id,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'jumlah' => $request->jumlah,
            'harga_per_satuan' => $harga_per_satuan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route(session()->get('role') . '.transaksi.index')
            ->with('success', 'Transaksi Bahan Baku berhasil diedit.');
    }

    public function destroy($id)
    {
        $historyBahanBaku = BahanBakuTransaksi::findOrFail($id);
        $historyBahanBaku->delete();
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
