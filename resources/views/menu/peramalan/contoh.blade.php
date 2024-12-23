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
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route(session()->get('role') . '.bahan_baku.create') }}" class="btn btn-success">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <style>
            .btn.btn-primary:hover {
                background-color: #007bff;
                /* Ganti dengan warna yang Anda inginkan */
                border-color: #007bff;
                /* Ganti dengan warna border yang sesuai */
            }

            /* Jika Anda ingin menyesuaikan tombol lainnya */
            .btn.btn-primary:focus,
            .btn.btn-primary:active {
                background-color: #007bff;
                /* Warna saat di-focus atau aktif */
                border-color: #007bff;
            }

            #stok_rendah_filter {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .dt-buttons {
                order: -1;
                /* Memastikan tombol PDF di kiri */
            }

            .dataTables_filter {
                display: flex;
                justify-content: flex-end;
                /* Memastikan filter pencarian di kanan */
            }
        </style>

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs mb-3" id="bahanBakuTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="allData-tab" data-bs-toggle="tab" href="#allData" role="tab"
                    aria-controls="allData" aria-selected="true">Semua Data</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="lowStock-tab" data-bs-toggle="tab" href="#lowStock" role="tab"
                    aria-controls="lowStock" aria-selected="false">Stok Rendah</a>
            </li>
        </ul>
        <div class="tab-content" id="bahanBakuTabsContent">
            <!-- Tab: Semua Data -->
            <div class="tab-pane fade show active" id="allData" role="tabpanel" aria-labelledby="allData-tab">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Bahan Baku</th>
                                        <th>Stok Sekarang</th>
                                        <th>Stok Minimum</th>
                                        <th data-sortable="false">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $bahan_baku)
                                        <tr
                                            class="{{ $bahan_baku->stok < $bahan_baku->stok_minimal ? 'table-danger' : '' }}">
                                            <td>{{ $bahan_baku->nama_bahan_baku }}</td>
                                            <td>{{ number_format($bahan_baku->stok, 0, ',', '.') }}
                                                {{ $bahan_baku->satuan }}</td>
                                            <td>{{ number_format($bahan_baku->stok_minimal, 0, ',', '.') }}
                                                {{ $bahan_baku->satuan }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-three-dots me-1"></i>
                                                        <!-- Ikon tiga titik horizontal -->
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <!-- Tombol Edit -->
                                                        <li>
                                                            <a href="{{ route(session()->get('role') . '.bahan_baku.edit', $bahan_baku->bahan_baku_id) }}"
                                                                class="dropdown-item">
                                                                <i class="bi bi-pencil-fill me-1"></i> Edit
                                                            </a>
                                                        </li>

                                                        <!-- Tombol Histori -->
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-bs-toggle="modal" data-bs-target="#historiModal"
                                                                data-bahan-baku-id="{{ $bahan_baku->bahan_baku_id }}">
                                                                <i class="bi bi-bar-chart me-1"></i> Histori
                                                            </button>
                                                        </li>

                                                        <!-- Tombol Hapus -->
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                                data-form-id="delete-form-{{ $bahan_baku->bahan_baku_id }}">
                                                                <i class="bi bi-trash-fill me-1"></i> Hapus
                                                            </button>
                                                        </li>

                                                        <!-- Form Hapus -->
                                                        <form id="delete-form-{{ $bahan_baku->bahan_baku_id }}"
                                                            action="{{ route(session()->get('role') . '.bahan_baku.destroy', $bahan_baku->bahan_baku_id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab: Stok Rendah -->
            <div class="tab-pane fade" id="lowStock" role="tabpanel" aria-labelledby="lowStock-tab">
                <div class="card radius-10 w-100">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="stok_rendah" class="table align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Bahan Baku</th>
                                        <th>Stok Sekarang</th>
                                        <th>Stok Minimum</th>
                                        <th>Prediksi Pembelian</th>
                                        <th>Harga Per Satuan</th>
                                        <th>Supplier Terakhir</th>
                                        <th data-sortable="false">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $bahan_baku)
                                        @if ($bahan_baku->stok < $bahan_baku->stok_minimal)
                                            <tr class="table-danger">
                                                <td>{{ $bahan_baku->nama_bahan_baku }}</td>
                                                <td>
                                                    {{ number_format($bahan_baku->stok, 0, ',', '.') }}
                                                    {{ $bahan_baku->satuan }}
                                                </td>
                                                <td>
                                                    {{ number_format($bahan_baku->stok_minimal, 0, ',', '.') }}
                                                    {{ $bahan_baku->satuan }}
                                                </td>
                                                <td>
                                                    <em><strong>{{ number_format($bahan_baku->rata_rata, 0, ',', '.') }}
                                                            {{ $bahan_baku->satuan }}</strong></em>
                                                </td>
                                                <td>
                                                    <em>
                                                        <strong>Rp.
                                                            {{ number_format($bahan_baku->harga_per_satuan, 0, ',', '.') }}/{{ $bahan_baku->satuan }}
                                                        </strong>
                                                    </em>
                                                </td>
                                                <td><em><strong>{{ $bahan_baku->supplier_terakhir }}</strong></em></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary" type="button"
                                                            id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots me-1"></i>
                                                            <!-- Ikon tiga titik horizontal -->
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <!-- Tombol Edit -->
                                                            <li>
                                                                <a href="{{ route(session()->get('role') . '.bahan_baku.edit', $bahan_baku->bahan_baku_id) }}"
                                                                    class="dropdown-item">
                                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                                </a>
                                                            </li>

                                                            <!-- Tombol Histori -->
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal" data-bs-target="#historiModal"
                                                                    data-bahan-baku-id="{{ $bahan_baku->bahan_baku_id }}">
                                                                    <i class="bi bi-bar-chart me-1"></i> Histori
                                                                </button>
                                                            </li>

                                                            <!-- Tombol Hapus -->
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#confirmDeleteModal"
                                                                    data-form-id="delete-form-{{ $bahan_baku->bahan_baku_id }}">
                                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                                </button>
                                                            </li>

                                                            <!-- Form Hapus -->
                                                            <form id="delete-form-{{ $bahan_baku->bahan_baku_id }}"
                                                                action="{{ route(session()->get('role') . '.bahan_baku.destroy', $bahan_baku->bahan_baku_id) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Modal Histori -->
    <div class="modal fade" id="historiModal" tabindex="-1" aria-labelledby="historiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historiModalLabel">Histori Bahan Baku 12 bulan Terakhir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <canvas id="chartHistori" width="900" height="450"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('[data-bs-target="#confirmDeleteModal"]');
            var confirmDeleteButton = document.getElementById('confirm-delete');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var formId = button.getAttribute('data-form-id');
                    confirmDeleteButton.setAttribute('data-form-id', formId);
                });
            });

            confirmDeleteButton.addEventListener('click', function() {
                var formId = confirmDeleteButton.getAttribute('data-form-id');
                document.getElementById(formId).submit();
            });

            // Perbarui tampilan nya agar lebih bagus? ubah warnanya
            var chart;
            $('#historiModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var bahanBakuId = button.data('bahan-baku-id');
                if (chart) {
                    chart.destroy();
                }

                const base_url = "{{ url('/') }}"; // Base URL Laravel
                const role = "{{ session('role') }}"; // Role dari session Laravel

                $.ajax({
                    url: `${base_url}/${role}/bahan-baku/histori`,
                    method: 'GET',
                    data: {
                        bahan_baku_id: bahanBakuId,
                        range_bulan: 12
                    },
                    success: function(response) {
                        var dataItem = response[0];
                        var labels = dataItem.nama_bulan;
                        var dataKeluar = dataItem.data_keluar;
                        var dataMasuk = dataItem.data_masuk;
                        var satuan = dataItem.bahan_baku.satuan;
                        var numberFormat = new Intl.NumberFormat('id-ID');

                        // Pastikan nilai null atau undefined diganti dengan 0
                        dataKeluar = dataKeluar.map(function(value) {
                            return value || 0;
                        });
                        dataMasuk = dataMasuk.map(function(value) {
                            return value || 0;
                        });

                        var smaDataKeluar = 0;
                        if (dataKeluar.length >= 3) {
                            var lastThreeMonths = dataKeluar.slice(-3);
                            var lastThreeMonthsInt = lastThreeMonths.map(function(value) {
                                return parseInt(value, 10);
                            });
                            smaDataKeluar = lastThreeMonthsInt.reduce(function(acc, val) {
                                return acc + val;
                            }, 0) / 3;

                            smaDataKeluar = Math.round(
                                smaDataKeluar); // Membulatkan hasil pembagian
                        }

                        var smaDataMasuk = 0;
                        if (dataMasuk.length >= 3) {
                            var lastThreeMonths = dataMasuk.slice(-3);
                            var lastThreeMonthsInt = lastThreeMonths.map(function(value) {
                                return parseInt(value, 10);
                            });
                            smaDataMasuk = lastThreeMonthsInt.reduce(function(acc, val) {
                                return acc + val;
                            }, 0) / 3;

                            smaDataMasuk = Math.round(
                                smaDataMasuk); // Membulatkan hasil pembagian
                        }


                        labels.push('Bulan Selanjutnya (Prediksi)');
                        dataMasuk.push(smaDataMasuk);
                        dataKeluar.push(smaDataKeluar);
                        const lastIndex = dataKeluar.length - 1;
                        const dataKeluarBackgroundColor = dataKeluar.map(function(value,
                            index) {
                            // Jika ini adalah indeks terakhir, warnai dengan hijau
                            return index === lastIndex ? 'rgba(75, 192, 192, 0.8)' :
                                'rgba(255, 99, 132, 0.8)';
                        });


                        var ctx = document.getElementById('chartHistori').getContext('2d');

                        chart = new Chart(ctx, {
                            type: 'bar', // Tipe chart bar
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Masuk',
                                    data: dataMasuk,
                                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    stack: 'stack1' // Menandakan dataset ini berada dalam satu stack
                                }, {
                                    label: 'Jumlah Keluar',
                                    data: dataKeluar,
                                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    stack: 'stack1' // Menandakan dataset ini berada dalam satu stack
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 1500,
                                    easing: 'easeInOutQuart'
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        stacked: true, // Aktifkan stacked untuk y-axis
                                        suggestedMax: Math.max(...dataMasuk.concat(
                                            dataKeluar)) * 1.2,
                                        grid: {
                                            color: 'rgba(0, 0, 0, 0.05)',
                                            borderDash: [8, 4],
                                            drawBorder: false
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return numberFormat.format(value) +
                                                    ' ' + satuan;
                                            },
                                            color: 'rgba(0, 0, 0, 0.7)',
                                            font: {
                                                size: 11,
                                                family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                                            },
                                            padding: 10,
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: 'rgba(0, 0, 0, 0.7)',
                                            font: {
                                                size: 11,
                                                family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                                            },
                                            padding: 10,
                                            maxRotation: 90, // Set max rotation 90 degrees for vertical text
                                            minRotation: 70
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: 'rgba(0, 0, 0, 0.8)',
                                            font: {
                                                size: 12,
                                                weight: '600',
                                                family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                                            },
                                            padding: 20
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                                        titleColor: 'white',
                                        bodyColor: 'white',
                                        titleFont: {
                                            size: 13,
                                            weight: 'bold',
                                            family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                                        },
                                        bodyFont: {
                                            size: 12,
                                            family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif"
                                        },
                                        padding: 12,
                                        cornerRadius: 6,
                                        displayColors: true,
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                return numberFormat.format(
                                                        tooltipItem.raw) + ' ' +
                                                    satuan;
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: 'white',
                                        font: {
                                            weight: '600',
                                            size: 11,
                                            family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
                                        },
                                        formatter: function(value, context) {
                                            return numberFormat.format(value);
                                        },
                                        display: function(context) {
                                            return context.dataset.data[context
                                                    .dataIndex] >
                                                0;
                                        },
                                        anchor: 'center',
                                        align: 'bottom',
                                        offset: -10,
                                    }

                                },
                                layout: {
                                    padding: {
                                        top: 0,
                                        bottom: 25,
                                        left: 15,
                                        right: 15
                                    }
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true,
                                    animationDuration: 200
                                }
                            },
                            plugins: [ChartDataLabels]
                        });
                    }
                });
            });

        });
    </script>
@endsection
