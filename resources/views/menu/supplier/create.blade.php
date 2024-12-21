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
                            <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route(session()->get('role') . '.supplier.index') }}">Supplier</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Tambah Supplier</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route(session()->get('role') . '.supplier.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label" for="nama_supplier">Nama Supplier</label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                class="form-control @error('nama_supplier') is-invalid @enderror"
                                value="{{ old('nama_supplier') }}" placeholder="Masukkan nama supplier">
                            @error('nama_supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="alamat_supplier">Alamat Supplier</label>
                            <textarea id="alamat_supplier" name="alamat_supplier"
                                class="form-control @error('alamat_supplier') is-invalid @enderror" rows="3"
                                placeholder="Masukkan alamat supplier">{{ old('alamat_supplier') }}</textarea>
                            @error('alamat_supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="kontak_supplier">Kontak Supplier</label>
                            <input type="text" id="kontak_supplier" name="kontak_supplier"
                                class="form-control @error('kontak_supplier') is-invalid @enderror"
                                value="{{ old('kontak_supplier') }}" placeholder="Masukkan kontak supplier">
                            @error('kontak_supplier')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih bahan baku</label>
                            <select name="bahan_baku[]" class="bahan-baku-select @error('bahan_baku') is-invalid @enderror"
                                multiple="multiple">
                                @foreach ($bahanBakus as $bahanBaku)
                                    <option value="{{ $bahanBaku->bahan_baku_id }}"
                                        @if (in_array($bahanBaku->bahan_baku_id, old('bahan_baku', []))) selected @endif>
                                        {{ $bahanBaku->nama_bahan_baku }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_baku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route(session()->get('role') . '.supplier.index') }}"
                                class="btn btn-dark">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".bahan-baku-select").select2({
                theme: "bootstrap4",
                width: "100%",
                placeholder: 'Pilih bahan baku',
                allowClear: true,
                maximumSelectionLength: 5,
                language: {
                    noResults: function() {
                        return "Tidak ada data yang dicari";
                    },
                    maximumSelected: function(args) {
                        return "Anda hanya dapat memilih maksimal " + args.maximum + " bahan baku.";
                    }
                }
            });
        });
    </script>
@endsection
