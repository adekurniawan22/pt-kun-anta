@extends('layout.main')

@section('style')
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
            font-size: 0.9rem !important;
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
            font-size: 0.9rem !important;
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

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="tipe" class="form-label">Tipe transaksi</label>
                                    <select name="tipe" id="tipe"
                                        class="form-select @error('tipe') is-invalid @enderror">
                                        <option value="">Pilih tipe transaksi</option>
                                        <option value="masuk"
                                            {{ old('tipe', $bahanBakuTransaksi->tipe) === 'masuk' ? 'selected' : '' }}>Masuk
                                        </option>
                                        <option value="keluar"
                                            {{ old('tipe', $bahanBakuTransaksi->tipe) === 'keluar' ? 'selected' : '' }}>
                                            Keluar</option>
                                    </select>
                                    @error('tipe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                                    <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                        class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                                        value="{{ old('tanggal_transaksi', $bahanBakuTransaksi->tanggal_transaksi) }}">
                                    @error('tanggal_transaksi')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12">
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
                            </div>

                            <div class="col-12">
                                <div id="supplier_container" class="form-group mb-3" style="display: none">
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
                            </div>

                            <div class="col-6">
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
                            </div>

                            <div class="col-6">
                                <div class="form-group mb-3" id="harga_per_satuan_container" style="display: none">
                                    <label class="form-label" for="harga_per_satuan">Harga Per Satuan</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp.</span>
                                        <input type="text" id="harga_per_satuan" name="harga_per_satuan"
                                            class="form-control harga-input @error('harga_per_satuan') is-invalid @enderror"
                                            value="{{ old('harga_per_satuan', $bahanBakuTransaksi->harga_per_satuan) }}"
                                            placeholder="Silakan masukkan harga yang valid">
                                    </div>
                                    @error('harga_per_satuan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
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
                            </div>
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
            $('.select2-bahan-baku').select2({
                placeholder: 'Pilih bahan baku',
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
                placeholder: 'Pilih supplier',
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

            function toggleSupplier() {
                const tipe = $('#tipe').val();
                const supplier_container = $('#supplier_container');
                const harga_per_satuan_container = $('#harga_per_satuan_container');
                const supplierSelect = $(`#supplier_id`);
                const bahanBakuSelect = $(`#bahan_baku_id`);
                const jumlahCol = $(`#jumlah`).parent().parent();
                const hargaCol = $(`#harga`).parent().parent();
                const hargaInput = $(`#harga`);

                if (tipe === 'masuk') {
                    jumlahCol.removeClass('col-12').addClass('col-6');
                    hargaCol.show();
                    supplier_container.slideDown();
                    harga_per_satuan_container.slideDown();

                    const bahanBakuId = bahanBakuSelect.val();
                    loadSuppliers(bahanBakuId, supplierSelect);
                } else {
                    jumlahCol.removeClass('col-6').addClass('col-12');
                    supplier_container.slideUp();
                    hargaCol.hide();
                    harga_per_satuan_container.slideUp();
                }
            }

            $(document).on('change', '.select2-bahan-baku', function() {
                const tipeTransaksi = $(`#tipe`).val();
                const supplierSelect = $(`#supplier_id`);

                if (tipeTransaksi === 'masuk') {
                    const bahanBakuId = $(this).val();
                    loadSuppliers(bahanBakuId, supplierSelect);
                }
            });

            document.querySelectorAll('.harga-input').forEach(function(input) {
                if (input.value) {
                    let rawValue = input.value.replace(/[^0-9]/g, '');
                    input.value = new Intl.NumberFormat('id-ID').format(rawValue);
                }
            });

            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('harga-input')) {
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    e.target.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });

            function loadSuppliers(bahanBakuId, supplierSelect) {
                if (bahanBakuId) {
                    $.ajax({
                        url: `{{ route('suppliers.by-bahan-baku', ['bahanBakuId' => ':bahanBakuId']) }}`
                            .replace(':bahanBakuId', bahanBakuId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log(data);
                            const labelElement = $(`label[for="supplier_id"]`);

                            supplierSelect.empty();
                            supplierSelect.append('<option value="">Pilih supplier</option>');

                            $.each(data, function(key, value) {
                                supplierSelect.append(
                                    `<option value="${value.supplier_id}">${value.nama_supplier}</option>`
                                );
                            });

                            labelElement.html(
                                `Supplier <span class="text-danger">*</span><br><small class="text-muted fst-italic">(Ada ${data.length} Supplier)</small>`
                            );

                            if (data.length > 0) {
                                supplierSelect.prop('selectedIndex',
                                    1
                                );
                                supplierSelect.trigger('change');
                            }
                        }
                    });
                } else {
                    const labelElement = $(`label[for="supplier_id"]`);

                    supplierSelect.empty();
                    supplierSelect.append('<option value="">Pilih Supplier</option>');
                    labelElement.html(`Supplier <span class="text-danger">*</span>`);
                }
            }

            $('#tipe').on('change', function() {
                toggleSupplier();
            });

            document.querySelector('form').addEventListener('submit', function() {
                this.querySelectorAll('.harga-input').forEach(function(input) {
                    let rawValue = input.value.replace(/\./g, '');
                    input.value = rawValue;
                });
            });

            toggleSupplier();
        });
    </script>
@endsection
