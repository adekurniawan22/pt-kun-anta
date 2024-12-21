@extends('layout.main')
@section('content')
    <main class="page-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3"
            style="height: 37px; overflow: hidden; display: flex; align-items: center;">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="<?= route(session()->get('role') . '.dashboard') ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= route(session()->get('role') . '.bahan_baku.index') ?>">Bahan Baku</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Edit Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route(session()->get('role') . '.bahan_baku.update', $bahanBaku->bahan_baku_id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="form-label" for="kode_bahan_baku">Kode Bahan Baku</label>
                            <input type="text" id="kode_bahan_baku" name="kode_bahan_baku"
                                class="form-control @error('kode_bahan_baku') is-invalid @enderror"
                                value="{{ old('kode_bahan_baku', $bahanBaku->kode_bahan_baku) }}"
                                placeholder="Masukkan kode bahan baku">
                            @error('kode_bahan_baku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="nama_bahan_baku">Nama Bahan Baku</label>
                            <input type="text" id="nama_bahan_baku" name="nama_bahan_baku"
                                class="form-control @error('nama_bahan_baku') is-invalid @enderror"
                                value="{{ old('nama_bahan_baku', $bahanBaku->nama_bahan_baku) }}"
                                placeholder="Masukkan nama bahan baku">
                            @error('nama_bahan_baku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <select name="satuan" id="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                <option value="">Pilih satuan</option>
                                <option value="Liter" {{ old('satuan', $bahanBaku->satuan) == 'Liter' ? 'selected' : '' }}>
                                    Liter
                                </option>
                                <option value="Kilogram"
                                    {{ old('satuan', $bahanBaku->satuan) == 'Kilogram' ? 'selected' : '' }}>
                                    Kilogram
                                </option>
                            </select>
                            @error('satuan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="stok_minimal">Stok Minimum</label>
                            <input type="number" min="1" id="stok_minimal" name="stok_minimal"
                                class="form-control @error('stok_minimal') is-invalid @enderror"
                                value="{{ old('stok_minimal', $bahanBaku->stok_minimal) }}"
                                placeholder="Masukkan jumlah stok minimum">
                            @error('stok_minimal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route(session()->get('role') . '.bahan_baku.index') }}"
                                class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
