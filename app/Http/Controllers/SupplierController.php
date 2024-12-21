<?php

namespace App\Http\Controllers;

use App\Models\{Supplier, BahanBaku};
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private const TITLE_INDEX = 'Daftar Supplier';
    private const TITLE_CREATE = 'Tambah Supplier';
    private const TITLE_EDIT = 'Edit Supplier';

    public function index()
    {
        $data = Supplier::with('pengguna')->get();
        return view('menu.supplier.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        $bahanBakus = BahanBaku::all();
        return view('menu.supplier.create', [
            'title' => self::TITLE_CREATE,
            'bahanBakus' => $bahanBakus,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $this->validateStoreOrUpdate($request);

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'kontak_supplier' => $request->kontak_supplier,
            'bahan_baku' => $request->has('bahan_baku') ? array_map('intval', $request->bahan_baku) : null,
            'dibuat_oleh' => session()->get('pengguna_id'),
        ]);

        return redirect()->route(session()->get('role') . '.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $selectedBahanBaku = $supplier->bahan_baku;
        $bahanBakus = BahanBaku::all();
        return view('menu.supplier.edit', [
            'title' => self::TITLE_EDIT,
            'supplier' => $supplier,
            'selectedBahanBaku' => $selectedBahanBaku,
            'bahanBakus' => $bahanBakus,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirim
        $this->validateStoreOrUpdate($request);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'nama_supplier' => $request->nama_supplier,
            'alamat_supplier' => $request->alamat_supplier,
            'kontak_supplier' => $request->kontak_supplier,
            'bahan_baku' => $request->bahan_baku ? array_map('intval', $request->bahan_baku) : null,
        ]);

        return redirect()->route(session()->get('role') . '.supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            Supplier::findOrFail($id)->delete();
            return redirect()->route(session()->get('role') . '.supplier.index')
                ->with('success', 'Supplier berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route(session()->get('role') . '.supplier.index')
                ->with('error', 'Supplier ini tidak dapat dihapus karena masih terdapat data supplier yang terkait dalam transaksi.');
        }
    }


    private function validateStoreOrUpdate(Request $request)
    {
        $rules = [
            'nama_supplier' => 'required|string|max:100',
            'alamat_supplier' => 'required|string|max:255',
            'kontak_supplier' => 'required|string|max:50',
            'bahan_baku' => 'required|array|min:1',
        ];

        $customAttributes = [
            'nama_supplier' => 'Nama Supplier',
            'alamat_supplier' => 'Alamat Supplier',
            'kontak_supplier' => 'Kontak Supplier',
            'bahan_baku' => 'Bahan Baku',
        ];

        return $request->validate($rules, [], $customAttributes);
    }

    public function getSuppliersByBahanBaku($bahanBakuId)
    {
        $suppliers = Supplier::all();
        $filteredSuppliers = $suppliers->filter(function ($supplier) use ($bahanBakuId) {
            return in_array($bahanBakuId, $supplier->bahan_baku);
        });

        $filteredSuppliers = $filteredSuppliers->values();
        return response()->json($filteredSuppliers);
    }
}
