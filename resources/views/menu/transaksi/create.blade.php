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
                            <a href="<?= route(session()->get('role') . '.transaksi.index') ?>">Transaksi</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Tambah Transaksi</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form id="transactionForm" action="{{ route(session()->get('role') . '.transaksi.store') }}"
                        method="POST">
                        @csrf
                        <div id="transaksi-container" class="row">
                            <!-- Initial transaction form will be added here -->
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <button type="button" class="btn btn-primary" id="btn-add-transaksi">+ Form</button>
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
            let transactionCount = 0;

            function getTransactionTemplate(index, bahanBaku, isMultiple = false) {
                const colClass = isMultiple ? 'col-md-6' : 'col-12';
                return `
                    <div id="transaksi-form-${index}" class="transaksi-form ${colClass} mb-4 ">
                      <div class="card h-100 text-dark" style="background-color: rgba(211, 211, 211, 0.2); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                           ${isMultiple ? 
                           `<div class="card-header" 
                                                                                                                                                        style="background-color: rgba(0, 0, 0, 0.8); color: white; position: relative; padding: 10px;">
                                                                                                                                                            Transaksi ${transactionCount}
                                                                                                                                                            <button type="button" class="btn btn-danger py-0 btn-remove-transaksi" data-index="${transactionCount}" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); font-size: 0.9em !important; ">
                                                                                                                                                                Hapus
                                                                                                                                                            </button>
                                                                                                                                                        </div>` : ''}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label for="tipe_${index}" class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                                            <select name="tipe[]" id="tipe_${index}" class="form-select required tipe-transaksi">
                                                <option value="">Pilih Tipe Transaksi</option>
                                                <option value="masuk">Masuk</option>
                                                <option value="keluar">Keluar</option>
                                            </select>
                                            <div class="invalid-feedback">Silakan pilih tipe transaksi</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="tanggal_transaksi_${index}">Tanggal Transaksi <span class="text-danger">*</span></label>
                                            <input type="date" id="tanggal_transaksi_${index}" name="tanggal_transaksi[]" class="form-control required">
                                            <div class="invalid-feedback">Silakan pilih tanggal transaksi</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="bahan_baku_id_${index}" class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                                            <select name="bahan_baku_id[]" id="bahan_baku_id_${index}" class="form-select required select2-bahan-baku">
                                                <option value="">Pilih Bahan Baku</option>
                                                ${bahanBaku}
                                            </select>
                                            <div class="invalid-feedback">Silakan pilih bahan baku</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="supplier-container_${index} " style="display: none;">
                                            <div class="mb-3">
                                                <label for="supplier_id_${index}" class="form-label">Supplier <span class="text-danger">*</span></label>
                                                <select name="supplier_id[]" id="supplier_id_${index}" class="form-select select2-supplier">
                                                    <option value="">Pilih Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier['supplier_id'] }}">{{ $supplier['nama_supplier'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Silakan pilih supplier</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="jumlah_${index}">Jumlah <span class="text-danger">*</span></label>
                                            <input type="number" id="jumlah_${index}" name="jumlah[]" class="form-control required" min="1" placeholder="Masukkan jumlah bahan baku">
                                            <div class="invalid-feedback">Silakan masukkan jumlah yang valid</div>
                                        </div>
                                    </div>
                                    <div class="col-6" style='display:none;'>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="harga_${index}">Harga per satuan <span class="text-danger">*</span></label>
                                            <input type="number" id="harga_${index}" name="harga[]" class="form-control" min="1" placeholder="Masukkan harga per satuan">
                                            <div class="invalid-feedback">Silakan masukkan harga per satuan yang valid</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label" for="keterangan_${index}">Keterangan</label>
                                            <textarea id="keterangan_${index}" name="keterangan[]" class="form-control" rows="3" placeholder="Masukkan keterangan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            const bahanBakuOptions =
                `@foreach ($bahanBaku as $data)
                        <option value="{{ $data->bahan_baku_id }}">{{ $data->nama_bahan_baku }}</option>
                    @endforeach`;

            function addTransactionForm() {
                const currentForms = $('.transaksi-form').length;
                const isMultiple = currentForms >= 1;

                if (isMultiple) {
                    $('.transaksi-form').each(function() {
                        const card = $(this).find('.card');
                        if (card.find('.card-header').length === 0) {
                            card.prepend(
                                `<div class="card-header" style="background-color: rgba(0, 0, 0, 0.8); color: white; position: relative; padding: 10px;">
                                    Transaksi ${transactionCount}
                                    <button type="button" class="btn btn-danger py-0 btn-remove-transaksi" data-index="${transactionCount}" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); font-size: 0.9em !important;">
                                        Hapus
                                    </button>
                                </div>
                                `
                            );
                        }
                    });
                }

                if (currentForms === 1) {
                    $('.transaksi-form').first().removeClass('col-12').addClass('col-md-6');
                }

                const template = getTransactionTemplate(transactionCount, bahanBakuOptions, isMultiple);
                $('#transaksi-container').append(template);
                updateTransactionHeaders();
                updateRemoveButtons();
                transactionCount++;
            }

            function updateTransactionHeaders() {
                const forms = $('.transaksi-form');
                forms.each(function(index) {
                    const cardHeader = $(this).find('.card-header');
                    const formId = $(this).attr('id');
                    const formIndex = formId.split('-')[2];

                    cardHeader.text(`Transaksi ${index + 1}`);
                    const removeButton = cardHeader.find('.btn-remove-transaksi');
                    if (removeButton.length === 0) {
                        cardHeader.append(
                            `<button type="button" class="btn btn-danger py-0 btn-remove-transaksi" data-index="${formIndex}" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); font-size: 0.9em !important;">
                                Hapus
                            </button>`
                        );
                    } else {
                        removeButton.attr('data-index',
                            index);
                    }

                    if (forms.length === 1) {
                        cardHeader.remove();
                    } else {
                        cardHeader.show();
                    }
                });
            }

            function updateRemoveButtons() {
                const forms = $('.transaksi-form').length;
                if (forms > 1) {
                    $('.btn-remove-transaksi').show();
                } else {
                    $('.btn-remove-transaksi').hide();
                }
            }

            $('#btn-add-transaksi').click(function() {
                addTransactionForm();
            });

            $(document).on('click', '.btn-remove-transaksi', function() {
                const index = $(this).data('index');
                $(`#transaksi-form-${index}`).remove();

                updateTransactionHeaders();
                updateRemoveButtons();

                const remainingForms = $('.transaksi-form').length;
                if (remainingForms === 1) {
                    $('.transaksi-form').removeClass('col-md-6').addClass('col-12');
                }
            });

            $('#transactionForm').on('submit', function(e) {
                e.preventDefault();
                let isValid = true;

                $('.is-invalid').removeClass('is-invalid');

                $('.transaksi-form').each(function() {
                    const form = $(this);

                    form.find('.required').each(function() {
                        if (!$(this).val()) {
                            $(this).addClass('is-invalid');

                            if ($(this).hasClass('select2-hidden-accessible')) {
                                $(this).next('.select2-container').find(
                                    '.select2-selection').addClass('is-invalid');
                            }

                            isValid = false;
                        }
                    });

                    const jumlah = form.find('input[name="jumlah[]"]');
                    if (jumlah.val() <= 0) {
                        jumlah.addClass('is-invalid');
                        isValid = false;
                    }

                    const harga = form.find('input[name="harga[]"].required');
                    if (harga.val() <= 0) {
                        harga.addClass('is-invalid');
                        isValid = false;
                    }
                });

                if (isValid) {
                    this.submit();
                } else {
                    const firstError = $('.is-invalid').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                }
            });

            function initializeSelect2(container) {
                container.find('.select2-bahan-baku').select2({
                    theme: "default",
                    width: '100%',
                    placeholder: 'Pilih Bahan Baku',
                    language: {
                        noResults: function() {
                            return "Data tidak ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                });

                container.find('.select2-supplier').select2({
                    theme: "default",
                    width: '100%',
                    placeholder: 'Pilih Supplier',
                    language: {
                        noResults: function() {
                            return "Data tidak ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                });
            }

            $(document).on('change', '.tipe-transaksi', function() {
                const index = $(this).attr('id').split('_')[1];
                const supplierContainer = $(`.supplier-container_${index}`);
                const supplierSelect = $(`#supplier_id_${index}`);
                const bahanBakuSelect = $(`#bahan_baku_id_${index}`);
                const jumlahCol = $(`#jumlah_${index}`).parent().parent();
                const hargaCol = $(`#harga_${index}`).parent().parent();
                const hargaInput = $(`#harga_${index}`);

                if ($(this).val() === 'keluar') {
                    jumlahCol.removeClass('col-6').addClass('col-12');
                    hargaCol.hide();
                    hargaInput.removeClass('required');
                } else if ($(this).val() === 'masuk') {
                    jumlahCol.removeClass('col-12').addClass('col-6');
                    hargaCol.show();
                    hargaInput.addClass('required');
                }

                if ($(this).val() === 'masuk') {
                    supplierContainer.slideDown();
                    supplierSelect.addClass('required');

                    const bahanBakuId = bahanBakuSelect.val();
                    loadSuppliers(bahanBakuId, supplierSelect);
                } else {
                    supplierContainer.slideUp();
                    supplierSelect.removeClass('required').removeClass('is-invalid');
                    supplierSelect.val('').trigger('change');
                }
            });

            $(document).on('change', '.select2-bahan-baku', function() {
                const index = $(this).attr('id').split('_')[3];
                const tipeTransaksi = $(`#tipe_${index}`).val();
                const supplierSelect = $(`#supplier_id_${index}`);

                if (tipeTransaksi === 'masuk') {
                    const bahanBakuId = $(this).val();
                    loadSuppliers(bahanBakuId, supplierSelect);
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
                            const index = supplierSelect.attr('id').split('_')[2];
                            const labelElement = $(`label[for="supplier_id_${index}"]`);

                            supplierSelect.empty();
                            supplierSelect.append('<option value="">Pilih Supplier</option>');

                            $.each(data, function(key, value) {
                                supplierSelect.append(
                                    `<option value="${value.supplier_id}">${value.nama_supplier}</option>`
                                );
                            });

                            // Update label dengan jumlah supplier
                            labelElement.html(
                                `Supplier <span class="text-danger">*</span><br><small class="text-muted fst-italic">(Ada ${data.length} Supplier)</small>`
                            );

                            // Pilih opsi pertama secara otomatis setelah mengisi data
                            if (data.length > 0) {
                                supplierSelect.prop('selectedIndex',
                                    1
                                ); // Pilih opsi kedua (indeks 1) karena indeks 0 adalah "Pilih Supplier"
                                supplierSelect.trigger('change'); // Memicu event change
                            }
                        }
                    });
                } else {
                    const index = supplierSelect.attr('id').split('_')[2];
                    const labelElement = $(`label[for="supplier_id_${index}"]`);

                    supplierSelect.empty();
                    supplierSelect.append('<option value="">Pilih Supplier</option>');

                    // Kembalikan label ke default jika tidak ada supplier
                    labelElement.html(`Supplier <span class="text-danger">*</span>`);
                }
            }

            addTransactionForm();
            initializeSelect2($('#transaksi-container'));

            $('#btn-add-transaksi').on('click', function() {
                setTimeout(function() {
                    initializeSelect2($('#transaksi-container'));
                }, 100);
            });

            $(document).on('change', '.required', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endsection
