@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route(session()->get('role') . '.dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Transaksi Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route(session()->get('role') . '.transaksi.create') }}" class="btn btn-success">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <!-- Navtabs for Filtering -->
                    <div class="nav-tabs-container mb-3">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#" id="filter-all">All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#" id="filter-masuk">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#" id="filter-keluar">Keluar</a>
                            </li>
                        </ul>
                    </div>

                    <div class="table-responsive">
                        <table id="transaksi_table" class="table align-middle">
                            <thead>
                                <tr>
                                    <th width="15%;">Tanggal Transaksi</th>
                                    <th width="20%;">Informasi Bahan Baku</th>
                                    <th width="10%;">Tipe</th>
                                    <th width="45%;">Informasi Tambahan</th>
                                    <th width="10%;" data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $history)
                                    <tr data-tipe="{{ $history->tipe }}">
                                        <td
                                            data-order="{{ \Carbon\Carbon::parse($history->tanggal_transaksi)->format('Y-m-d') }}">
                                            {{ \Carbon\Carbon::parse($history->tanggal_transaksi)->locale('id')->isoFormat('D MMMM YYYY') }}
                                            <br>
                                            <small class="text-muted">oleh {{ $history->pengguna->nama }}</small>
                                        </td>
                                        <td>
                                            {{ $history->bahanBaku->nama_bahan_baku }}
                                            <br>
                                            <small>
                                                <strong>
                                                    {{ number_format($history->jumlah, 0, ',', '.') }}
                                                    {{ $history->bahanBaku->satuan }}
                                                </strong>
                                            </small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge text-start p-2 {{ $history->tipe == 'masuk' ? 'bg-success' : 'bg-danger' }}">
                                                <i
                                                    class="bi {{ $history->tipe == 'masuk' ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle' }}"></i>
                                                {{ ucfirst($history->tipe) }}

                                                @if ($history->total != 0)
                                                    <br>
                                                    <br>
                                                    Rp. {{ number_format($history->total, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if (isset($history->supplier->nama_supplier))
                                                <small>
                                                    Dibeli dari:
                                                    <strong>{{ $history->supplier->nama_supplier }}</strong> <br>
                                                </small>
                                                @if (isset($history->keterangan))
                                                    <small
                                                        style="word-wrap: break-word; white-space: normal;"><em>{{ $history->keterangan }}</em></small>
                                                @endif
                                            @else
                                                <small><em>Tidak ada</em></small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-secondary" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots me-1"></i>
                                                    <!-- Ikon tiga titik horizontal -->
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <!-- Tombol Edit -->
                                                    <li>
                                                        <a href="{{ route(session()->get('role') . '.transaksi.edit', $history->bahan_baku_transaksi_id) }}"
                                                            class="dropdown-item">
                                                            <i class="bi bi-pencil-fill me-1"></i> Edit
                                                        </a>
                                                    </li>

                                                    <!-- Tombol Hapus -->
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteModal"
                                                            data-form-id="delete-form-{{ $history->bahan_baku_transaksi_id }}">
                                                            <i class="bi bi-trash-fill me-1"></i> Hapus
                                                        </button>
                                                    </li>

                                                    <!-- Form Hapus -->
                                                    <form id="delete-form-{{ $history->bahan_baku_transaksi_id }}"
                                                        action="{{ route(session()->get('role') . '.transaksi.destroy', $history->bahan_baku_transaksi_id) }}"
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
    </main>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(document).ready(function() {
                // Inisialisasi DataTables
                var table = $('#transaksi_table').DataTable({
                    autoWidth: false,
                    oLanguage: {
                        sLengthMenu: "Tampilkan _MENU_ data",
                        sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        sEmptyTable: "Tidak ada data",
                        sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
                    },
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ["10", "25", "50", "Semua"],
                    ],
                    language: {
                        search: "Cari:",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "<i class='fa fa-angle-right'></i>",
                            previous: "<i class='fa fa-angle-left'></i>",
                        },
                    },
                    order: [
                        [0, "desc"]
                    ],
                });

                // Filter berdasarkan tipe
                $('#filter-all').on('click', function(e) {
                    e.preventDefault();
                    table.column(2).search('').draw(); // Hapus filter
                });

                $('#filter-masuk').on('click', function(e) {
                    e.preventDefault();
                    table.column(2).search('masuk').draw(); // Filter tipe 'masuk'
                });

                $('#filter-keluar').on('click', function(e) {
                    e.preventDefault();
                    table.column(2).search('keluar').draw(); // Filter tipe 'keluar'
                });
            });


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
        });
    </script>
@endsection
