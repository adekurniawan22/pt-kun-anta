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
                            <span class="text-dark">Supplier</span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route(session()->get('role') . '.supplier.create') }}" class="btn btn-success">
                    <i class="fadeIn animated bx bx-plus"></i>Tambah
                </a>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="mt-1"></div>
                        <table id="example" class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Supplier</th>
                                    <th>Alamat</th>
                                    <th>Kontak</th>
                                    <th>Dibuat oleh</th>
                                    <th data-sortable="false">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $supplier)
                                    <tr>
                                        <td>{{ $supplier->nama_supplier }}</td>
                                        <td class="text-truncate" data-bs-original-title="{{ $supplier->alamat_supplier }}"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            style="max-width: 250px; overflow: hidden; white-space: nowrap;">
                                            {{ $supplier->alamat_supplier }}
                                        </td>
                                        <td>
                                            @if ($supplier->kontak_supplier)
                                                +{{ $supplier->kontak_supplier }}
                                            @else
                                                <button class="btn btn-secondary btn-sm" style="gap: 5px;" disabled>
                                                    Tidak Tersedia
                                                </button>
                                            @endif
                                        </td>
                                        <td>{{ $supplier->pengguna->nama }}</td>
                                        <td>
                                            <div class="d-flex align-items-start justify-content-start gap-3 fs-6">
                                                {{-- Hubungi Supplier --}}
                                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $supplier->kontak_supplier) }}"
                                                    target="_blank" class="btn btn-success btn-sm" style="gap: 5px;">
                                                    <i class="bi bi-whatsapp"></i> Hubungi
                                                </a>

                                                <!-- Tombol Edit -->
                                                <a href="{{ route(session()->get('role') . '.supplier.edit', $supplier->supplier_id) }}"
                                                    class="btn btn-sm btn-warning text-white d-flex align-items-center">
                                                    <i class="bi bi-pencil-fill me-1"></i> Edit
                                                </a>

                                                <!-- Tombol Hapus -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger d-flex align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                    data-form-id="delete-form-{{ $supplier->supplier_id }}">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>

                                                <!-- Form Hapus -->
                                                <form id="delete-form-{{ $supplier->supplier_id }}"
                                                    action="{{ route(session()->get('role') . '.supplier.destroy', $supplier->supplier_id) }}"
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
@endsection
@section('script')
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
        });
    </script>
@endsection
