<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    private const TITLE_INDEX = 'Daftar Pengguna';
    private const TITLE_CREATE = 'Tambah Pengguna';
    private const TITLE_EDIT = 'Edit Pengguna';

    public function index()
    {
        $data = Pengguna::all();
        return view('menu.pengguna.index', [
            'data' => $data,
            'title' => self::TITLE_INDEX
        ]);
    }

    public function create()
    {
        return view('menu.pengguna.create', [
            'title' => self::TITLE_CREATE
        ]);
    }

    public function store(Request $request)
    {
        $this->validateStoreOrUpdate($request);

        Pengguna::create([
            'role' => $request->role,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengguna = Pengguna::findOrFail($id);

        return view('menu.pengguna.edit', [
            'pengguna' => $pengguna,
            'title' => self::TITLE_EDIT
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateStoreOrUpdate($request, $id);

        $user = Pengguna::findOrFail($id);

        $user->role = $request->role;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;

        if ($user->isDirty()) {
            $user->save();
            return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil diedit.');
        }

        return redirect()->route(session()->get('role') . '.pengguna.index')->with('info', 'Tidak ada perubahan yang dilakukan.');
    }

    public function destroy($id)
    {
        Pengguna::findOrFail($id)->delete();
        return redirect()->route(session()->get('role') . '.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    private function validateStoreOrUpdate(Request $request, $id = null)
    {
        $rules = [
            'role' => 'required',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:pengguna,email' . ($id ? ",$id,pengguna_id" : ''),
            'password' => $id ? 'nullable|string|min:8' : 'required|string|min:8',
        ];

        $customAttributes = [
            'role' => 'Role',
            'nama' => 'Nama Lengkap',
            'email' => 'Email',
            'password' => 'Password',
        ];

        return $request->validate($rules, [], $customAttributes);
    }
}
