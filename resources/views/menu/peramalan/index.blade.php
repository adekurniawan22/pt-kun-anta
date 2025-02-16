@extends('layout.main')
@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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
                            <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <input type="text" id="monthYearPicker" name="monthYear" class="form-control-sm" autocomplete="off"
                    placeholder="Pilih Bulan Prediksi" style="cursor: pointer">
            </div>
        </div>
        <!-- End Breadcrumb -->

        <style>
            .btn.btn-primary:hover {
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn.btn-primary:focus,
            .btn.btn-primary:active {
                background-color: #007bff;
                border-color: #007bff;
            }
        </style>

        @php
            $aturanBahanBaku = [
                [
                    'nama_bahan_baku' => 'Amidis',
                    'satuan' => 'Gram',
                    'pcs' => 'Galon',
                    'satuanPerPcs' => 19000,
                    'harga' => 22000,
                ],
                [
                    'nama_bahan_baku' => 'Biotin',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 250,
                    'harga' => 1663750,
                ],
                [
                    'nama_bahan_baku' => 'Musk Fragrance',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 500,
                    'harga' => 600000,
                ],
                [
                    'nama_bahan_baku' => 'Jojoba Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 2100000,
                ],
                [
                    'nama_bahan_baku' => 'Bakuchiol Ekstrak',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 4120000,
                ],
                [
                    'nama_bahan_baku' => 'Pewarna Hijau',
                    'satuan' => 'Gram',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 10,
                    'harga' => 5000,
                ],
                [
                    'nama_bahan_baku' => 'Charcoal',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 250,
                    'harga' => 40000,
                ],
                [
                    'nama_bahan_baku' => 'Coconut Oil',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 18000,
                    'harga' => 24000,
                ],
                [
                    'nama_bahan_baku' => 'Olive Oil',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 20000,
                    'harga' => 57000,
                ],
                [
                    'nama_bahan_baku' => 'Castor Oil',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 1000,
                    'harga' => 60000,
                ],
                [
                    'nama_bahan_baku' => 'Glycerin',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 26000,
                ],
                [
                    'nama_bahan_baku' => 'Koh',
                    'satuan' => 'Gram',
                    'pcs' => 'Karung',
                    'satuanPerPcs' => 25000,
                    'harga' => 30000,
                ],
                [
                    'nama_bahan_baku' => 'Natrosol',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 220000,
                ],
                [
                    'nama_bahan_baku' => 'Madu',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 7000,
                    'harga' => 53500,
                ],
                [
                    'nama_bahan_baku' => 'Shea Butter',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 215000,
                ],
                [
                    'nama_bahan_baku' => 'Calendula',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 500,
                    'harga' => 1042000,
                ],
                [
                    'nama_bahan_baku' => 'Daun Bidara',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 55000,
                ],
                [
                    'nama_bahan_baku' => 'Daun The Hijau',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 64000,
                ],
                [
                    'nama_bahan_baku' => 'Alcohol',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 1000,
                    'harga' => 13000,
                ],
                [
                    'nama_bahan_baku' => 'Tea Tree Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1300000,
                ],
                [
                    'nama_bahan_baku' => 'Eucalyptus Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 716000,
                ],
                [
                    'nama_bahan_baku' => 'Blackseed Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 235000,
                ],
                [
                    'nama_bahan_baku' => 'Centela Asiatica',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 400680,
                ],
                [
                    'nama_bahan_baku' => 'Spearmint',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1411000,
                ],
                [
                    'nama_bahan_baku' => 'Roman Chamomile',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1985500,
                ],
                [
                    'nama_bahan_baku' => 'Lavender Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1358500,
                ],
                [
                    'nama_bahan_baku' => 'Yuzu Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1915000,
                ],
                [
                    'nama_bahan_baku' => 'Licorice Ekstrak',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 888000,
                ],
                [
                    'nama_bahan_baku' => 'Vanila Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 2769000,
                ],
                [
                    'nama_bahan_baku' => 'Green Tea Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 2500000,
                ],
                [
                    'nama_bahan_baku' => 'Coco Glucoside',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 5000,
                    'harga' => 77000,
                ],
                [
                    'nama_bahan_baku' => 'Decyl Glucoside',
                    'satuan' => 'Gram',
                    'pcs' => 'Jerigen',
                    'satuanPerPcs' => 5000,
                    'harga' => 78000,
                ],
                [
                    'nama_bahan_baku' => 'Kukui Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 250,
                    'harga' => 25000,
                ],
                [
                    'nama_bahan_baku' => 'Ginger Oil',
                    'satuan' => 'Ml',
                    'pcs' => 'Botol',
                    'satuanPerPcs' => 1000,
                    'harga' => 1570000,
                ],
                [
                    'nama_bahan_baku' => 'SMCT',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 270000,
                ],
                [
                    'nama_bahan_baku' => 'Cocoyl Ishethionate',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 330000,
                ],
                [
                    'nama_bahan_baku' => 'Lauroyl Sarcosinate',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 170000,
                ],
                [
                    'nama_bahan_baku' => 'Wheat Protein',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 1200000,
                ],
                [
                    'nama_bahan_baku' => 'Coco Betain',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 1000,
                    'harga' => 100000,
                ],
                [
                    'nama_bahan_baku' => 'Citric Acid',
                    'satuan' => 'Gram',
                    'pcs' => 'Plastik',
                    'satuanPerPcs' => 100,
                    'harga' => 5000,
                ],
            ];
        @endphp

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="peramalan" class="table align-top table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bahan Baku</th>
                                    <th>Standarisasi</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Histori 3 Periode</th>
                                    <th>Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahan_baku as $data)
                                    <tr
                                        class="{{ number_format($data['stok_saat_ini'], 0, ',', '.') < number_format($data['stok_minimal'], 0, ',', '.') ? 'table-danger' : '' }}">
                                        <td>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0">{{ $data['nama_bahan_baku'] }}</h6>
                                                <p class="text-secondary mb-0" style="font-size: 12px;">
                                                    Kode: {{ $data['kode_bahan_baku'] }}
                                                </p>
                                            </div>
                                        </td>
                                        <td>
                                            {{ number_format($data['stok_minimal'], 0, ',', '.') }} {{ $data['satuan'] }}
                                        </td>
                                        <td>
                                            {{ number_format($data['stok_saat_ini'], 0, ',', '.') }} {{ $data['satuan'] }}
                                        </td>
                                        <td>
                                            <ul class="mb-0" style="padding-left: 15px;">
                                                @foreach ($data['data_bulanan'] as $bulan)
                                                    <li class="mb-1">
                                                        <span>
                                                            {{ $bulan['bulan'] }}
                                                            ({{ number_format($bulan['jumlah_keluar'], 0, ',', '.') }})
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <strong>Bulan: </strong> {{ $data['prediksi_bulan_selanjutnya']['bulan'] }}<br>
                                            <strong>Prediksi pembelian: </strong>
                                            {{ number_format($data['prediksi_bulan_selanjutnya']['jumlah_keluar'], 0, ',', '.') }}
                                            {{ $data['satuan'] }}

                                            {{-- Cari data bahan baku yang sesuai --}}
                                            @php
                                                $matchingBahanBaku = collect($aturanBahanBaku)->firstWhere(
                                                    'nama_bahan_baku',
                                                    $data['nama_bahan_baku'] ?? '',
                                                );

                                                $prediksi = floatval(
                                                    $data['prediksi_bulan_selanjutnya']['jumlah_keluar'],
                                                );
                                            @endphp

                                            {{-- Tampilkan informasi pembulatan jika ada data yang cocok dan prediksi > 0 --}}
                                            @if ($matchingBahanBaku && $prediksi > 0 && is_numeric($matchingBahanBaku['satuanPerPcs']))
                                                @php
                                                    $satuanPerPcs = floatval($matchingBahanBaku['satuanPerPcs']);
                                                    $jumlahPembulatan = ceil($prediksi / $satuanPerPcs);
                                                @endphp
                                                <small>
                                                    (
                                                    <em>
                                                        {{ $jumlahPembulatan }}
                                                        {{ $matchingBahanBaku['pcs'] }}
                                                    </em>
                                                    )
                                                </small>
                                            @endif

                                            <br>
                                            <strong>Supplier terakhir:</strong>
                                            {{ $data['supplier_terakhir']['nama_supplier'] ?? 'Belum ada' }}<br>

                                            @if ($matchingBahanBaku && $data['prediksi_bulan_selanjutnya']['jumlah_keluar'] > 0)
                                                <strong>Harga per {{ $matchingBahanBaku['pcs'] ?? '-' }}:</strong>
                                                {{ number_format($matchingBahanBaku['harga'], 0, ',', '.') }}
                                                <small>
                                                    (
                                                    <em>
                                                        menurut aturan bahan baku
                                                    </em>
                                                    )
                                                </small>
                                            @else
                                                <strong>Harga per {{ $data['satuan'] ?? '-' }} terakhir:</strong>
                                                {{ isset($data['supplier_terakhir']['harga_per_satuan']) && is_numeric($data['supplier_terakhir']['harga_per_satuan']) ? 'Rp. ' . number_format($data['supplier_terakhir']['harga_per_satuan'], 0, ',', '.') : 'Belum ada' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <div class="mt-2 text-end">
                        <button id="downloadPDF" class="btn btn-primary">
                            <i class="fadeIn animated bx bx-download me-1"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js">
    </script>
    <script>
        $(document).ready(function() {
            let firstDay; // Declare firstDay in wider scope
            var urlParams = new URLSearchParams(window.location.search);
            var bulanPilih = urlParams.get('prediksi');

            var currentDate = new Date();
            var currentMonth = currentDate.getMonth();
            var currentYear = currentDate.getFullYear();

            var defaultMonth = currentMonth + 1;
            var defaultYear = currentYear;

            if (bulanPilih) {
                var bulanTahunArray = bulanPilih.split('/');
                defaultYear = bulanTahunArray[2];
                defaultMonth = parseInt(bulanTahunArray[1]);
            }

            var defaultDate = defaultMonth + '/' + defaultYear;

            var bulanIndo = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Function to update firstDay
            function updateFirstDay(selectedDate) {
                var monthYear = selectedDate.split('/');
                var bulan = monthYear[0];
                var tahun = monthYear[1];

                var bulanAngka = bulanIndo.indexOf(bulan) + 1;
                var formattedBulan = ("0" + bulanAngka).slice(-2);
                firstDay = '01/' + formattedBulan + '/' + tahun;
                return firstDay;
            }

            $('#monthYearPicker').datepicker({
                format: "MM/yyyy",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                language: 'id',
            }).datepicker('setDate', defaultDate);

            // Set initial firstDay value
            firstDay = updateFirstDay($('#monthYearPicker').val());

            $('#monthYearPicker').on('changeDate', function(e) {
                var selectedDate = $('#monthYearPicker').val();
                var monthYear = selectedDate.split('/');
                var bulan = monthYear[0];
                var tahun = monthYear[1];

                var bulanAngka = bulanIndo.indexOf(bulan) + 1;
                var formattedBulan = ("0" + bulanAngka).slice(-2);

                var firstDay = '01/' + formattedBulan + '/' + tahun;

                // Update page URL
                var newUrl = window.location.href.split('?')[0] + '?prediksi=' + firstDay;
                window.location.href = newUrl;
            });

            const base_url = "{{ url('/') }}";
            const role = "{{ session('role') }}";

            $("#peramalan").DataTable({
                // dom: "Bfrtip",
                // buttons: [{
                //     text: '<i class="fadeIn animated bx bx-download me-1"></i> Download PDF',
                //     className: "btn btn-primary text-white mb-2",
                //     orientation: "portrait",
                //     pageSize: "A4",
                //     action: function(e, dt, node, config) {
                //         // Get current selected date
                //         var selectedDate = $('#monthYearPicker').val();
                //         var monthYear = selectedDate.split('/');
                //         var bulan = monthYear[0];
                //         var tahun = monthYear[1];

                //         var bulanAngka = bulanIndo.indexOf(bulan) + 1;
                //         var formattedBulan = ("0" + bulanAngka).slice(-2);
                //         var firstDay = '01/' + formattedBulan + '/' + tahun;

                //         // Update PDF URL with prediksi parameter
                //         var pdfUrl =
                //             `${base_url}/${role}/bahan-baku/peramalan/pdf?prediksi=${firstDay}`;
                //         window.open(pdfUrl, "_blank");
                //     },
                // }],
                oLanguage: {
                    sLengthMenu: "Tampilkan _MENU_ data",
                    sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    sEmptyTable: "Tidak ada data",
                    sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
                },
                language: {
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "<i class='fa fa-angle-right'></i>",
                        previous: "<i class='fa fa-angle-left'></i>",
                    },
                },
                initComplete: function() {
                    $(".dt-button").each(function() {
                        if ($(this).hasClass("buttons-pdf")) {
                            $(this).attr("id", "downloadPDF");
                        }
                    });
                    $("#peramalan_filter").prepend($(".dt-buttons"));
                },
                order: [
                    [2, 'asc']
                ], // Mengurutkan berdasarkan kolom ke-2 (stok_saat_ini) secara ascending
                columnDefs: [{
                    targets: 2,
                    type: 'num-fmt',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        } else if (type === 'sort') {
                            var stokSaatIni = parseInt(data.replace(/\./g, '').replace(/[^\d]/g,
                                ''));
                            return stokSaatIni;
                        }
                        return data;
                    }
                }]
            });

            // Add click handler for PDF button
            $('#downloadPDF').on('click', function() {
                var pdfUrl = `${base_url}/${role}/bahan-baku/peramalan/pdf?prediksi=${firstDay}`;
                window.open(pdfUrl, "_blank");
            });
        });
    </script>
@endsection
