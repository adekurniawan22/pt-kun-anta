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
                                    <tr>
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
                                            {{ $data['satuan'] }}<br>
                                            <strong>Supplier terakhir:</strong>
                                            {{ $data['supplier_terakhir']['nama_supplier'] ?? 'Belum ada' }}<br>

                                            <strong>Harga per {{ $data['satuan'] ?? '-' }} terakhir:</strong>
                                            {{ isset($data['supplier_terakhir']['harga_per_satuan']) && is_numeric($data['supplier_terakhir']['harga_per_satuan']) ? 'Rp. ' . number_format($data['supplier_terakhir']['harga_per_satuan'], 0, ',', '.') : 'Belum ada' }}
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
