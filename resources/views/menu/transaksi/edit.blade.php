@extends('layout.main')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
            background-color: #fff !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        .select2-selection--single.is-invalid {
            border-color: red !important;
        }


        .select2-container--focus .select2-selection--single.error-select2,
        .select2-container--open .select2-selection--single.error-select2 {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: .875em;
            color: #dc3545;
        }

        /* Perbaikan padding untuk tampilan arrow */
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            padding-right: 20px;
        }

        /* Style untuk clear button */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 25px;
            color: #6c757d;
        }

        /* Style untuk dropdown */
        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            margin-bottom: 5px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd;
        }

        .select2-container--default .select2-selection--single {
            padding-top: 4px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #212529 !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 4px !important;
            top: 2px !important
        }

        .select2-container--default .select2-dropdown {
            border-color: #86b7fe !important;
            border-radius: 0.375rem !important;
            margin-top: 4px !important;
        }

        .select2-container--default .select2-dropdown .select2-results__option--highlighted[aria-selected] {
            background-color: #0d6efd !important;
            color: #fff !important;
        }

        .select2-container--default .select2-dropdown .select2-results__option[aria-selected=true] {
            background-color: #e9ecef !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6c757d !important;
        }

        .select2-container--disabled .select2-selection {
            background-color: #e9ecef !important;
            opacity: 1;
        }

        .select2-search--dropdown .select2-search__field {
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            margin-bottom: 5px;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            outline: 0;
        }

        .select2-results__option {
            padding: 0.375rem 0.75rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: 2rem !important;
            margin-right: 0.5rem !important;
            background: none !important;
            border: none !important;
            padding: 0 !important;
            font-size: 1rem !important;
            color: #6c757d !important;
            margin-top: 1px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #dc3545 !important;
        }
    </style>
@endsection

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
                            <a href="<?= route(session()->get('role') . '.transaksi.index') ?>">Transaksi Bahan
                                Baku</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Edit Transaksi Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form
                        action="{{ route(session()->get('role') . '.transaksi.update', $bahanBakuTransaksi->bahan_baku_transaksi_id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="bahan_baku_id" class="form-label">Bahan Baku</label>
                            <select name="bahan_baku_id" id="bahan_baku_id"
                                class="form-select @error('bahan_baku_id') is-invalid @enderror select2-bahan-baku">
                                <option value="">Pilih Bahan Baku</option>
                                @foreach ($bahanBaku as $data)
                                    <option value="{{ $data->bahan_baku_id }}"
                                        {{ old('bahan_baku_id', $bahanBakuTransaksi->bahan_baku_id) == $data->bahan_baku_id ? 'selected' : '' }}>
                                        {{ $data->nama_bahan_baku }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_baku_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tipe" class="form-label">Tipe Transaksi</label>
                            <select name="tipe" id="tipe" class="form-select @error('tipe') is-invalid @enderror">
                                <option value="">Pilih Tipe Transaksi</option>
                                <option value="masuk"
                                    {{ old('tipe', $bahanBakuTransaksi->tipe) == 'masuk' ? 'selected' : '' }}>
                                    Masuk
                                </option>
                                <option value="keluar"
                                    {{ old('tipe', $bahanBakuTransaksi->tipe) == 'keluar' ? 'selected' : '' }}>
                                    Keluar
                                </option>
                            </select>
                            @error('tipe')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3" style="display: none">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id"
                                class="form-select select2-supplier @error('supplier_id') is-invalid @enderror">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}"
                                        {{ old('supplier_id', $bahanBakuTransaksi->supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="tanggal_transaksi">Tanggal Transaksi</label>
                            <input type="date" id="tanggal_transaksi" name="tanggal_transaksi"
                                class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                value="{{ old('tanggal_transaksi', $bahanBakuTransaksi->tanggal_transaksi ? $bahanBakuTransaksi->tanggal_transaksi : '') }}">
                            @error('tanggal_transaksi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="jumlah">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah"
                                class="form-control @error('jumlah') is-invalid @enderror"
                                value="{{ old('jumlah', $bahanBakuTransaksi->jumlah) }}"
                                placeholder="Masukkan jumlah bahan baku">
                            @error('jumlah')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                rows="3" placeholder="Masukkan keterangan">{{ old('keterangan', $bahanBakuTransaksi->keterangan) }}</textarea>
                            @error('keterangan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route(session()->get('role') . '.transaksi.index') }}"
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
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk Bahan Baku dan Supplier
            $('.select2-bahan-baku').select2({
                placeholder: 'Pilih Bahan Baku',
                allowClear: true,
                theme: 'default',
                width: '100%',
                minimumResultsForSearch: 0,
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            $('.select2-supplier').select2({
                placeholder: 'Pilih Supplier',
                allowClear: true,
                theme: 'default',
                width: '100%',
                minimumResultsForSearch: 0,
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            // Fungsi untuk menampilkan/menyembunyikan elemen Supplier dan menyesuaikan validasi
            function toggleSupplier() {
                const tipe = $('#tipe').val();
                const supplierField = $('#supplier_id');

                if (tipe === 'masuk') {
                    // Menampilkan elemen Supplier
                    supplierField.closest('.form-group').show();
                    // Menambahkan validasi required pada supplier_id
                    // supplierField.prop('required', true);
                } else {
                    // Menyembunyikan elemen Supplier jika tipe keluar
                    supplierField.closest('.form-group').hide();
                    // Menghapus nilai supplier_id dan tidak diperlukan saat tipe keluar
                    supplierField.prop('required', false);
                    supplierField.val('');
                }
            }

            // Event Listener untuk perubahan tipe transaksi
            $('#tipe').on('change', function() {
                toggleSupplier();
            });

            // Panggil fungsi toggleSupplier saat halaman dimuat untuk inisialisasi
            toggleSupplier();
        });
    </script>
@endsection
