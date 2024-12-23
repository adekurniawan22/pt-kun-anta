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

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bahan Baku</th>
                                    <th>Stok Minimum</th>
                                    <th>Stok Sekarang</th>
                                    <th>Dibuat oleh</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahan_baku as $data)
                                    <tr class="{{ $data->stok < $data->stok_minimal ? 'table-danger' : '' }}">
                                        <td>
                                            <div class="d-flex">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0">{{ $data->nama_bahan_baku }}</h6>
                                                    <p class="text-secondary mb-0" style="font-size: 12px">
                                                        Kode: {{ $data->kode_bahan_baku }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ number_format($data->stok_minimal, 0, ',', '.') }}
                                            {{ $data->satuan }}
                                        </td>
                                        <td>
                                            {{ number_format($data->stok, 0, ',', '.') }}
                                            {{ $data->satuan }}
                                        </td>
                                        <td>
                                            {{ $data->pengguna->nama }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start justify-content-start gap-3 fs-6">
                                                <!-- Tombol Histori -->
                                                <button type="button"
                                                    class="btn btn-sm btn-info text-white d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#historiModal"
                                                    data-bahan-baku-id="{{ $data->bahan_baku_id }}">
                                                    <i class="bi bi-bar-chart me-1"></i> Histori
                                                </button>

                                                <!-- Tombol Edit -->
                                                <a href="{{ route(session()->get('role') . '.bahan_baku.edit', $data->bahan_baku_id) }}"
                                                    class="btn btn-sm btn-warning text-white d-flex align-items-center">
                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                    data-form-id="delete-form-{{ $data->bahan_baku_id }}">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>

                                                <!-- Form Hapus -->
                                                <form id="delete-form-{{ $data->bahan_baku_id }}"
                                                    action="{{ route(session()->get('role') . '.bahan_baku.destroy', $data->bahan_baku_id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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

            var chart;
            $('#historiModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var bahanBakuId = button.data('bahan-baku-id');
                if (chart) {
                    chart.destroy();
                }

                const base_url = "{{ url('/') }}";
                const role = "{{ session('role') }}";

                $.ajax({
                    url: `${base_url}/${role}/bahan-baku/master/histori`,
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

                        const lastIndex = dataKeluar.length - 1;
                        const dataKeluarBackgroundColor = dataKeluar.map(function(value,
                            index) {
                            return index === lastIndex ? 'rgba(75, 192, 192, 0.8)' :
                                'rgba(255, 99, 132, 0.8)';
                        });

                        var ctx = document.getElementById('chartHistori').getContext('2d');

                        chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Masuk',
                                    data: dataMasuk,
                                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    stack: 'stack1'
                                }, {
                                    label: 'Jumlah Keluar',
                                    data: dataKeluar,
                                    backgroundColor: 'rgba(255, 99, 132, 0.8)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 2,
                                    borderRadius: 8,
                                    stack: 'stack1'
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
                                        stacked: true,
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
                                            maxRotation: 90,
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
