@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route(session()->get('role') . '.dashboard') }}"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Dashboard</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12">
                <h5 class="text-info">Statistik Umum</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Pengguna</p>
                                <h4 class="my-1 text-dark"> {{ $jumlahPengguna }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-person-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Supplier</p>
                                <h4 class="my-1 text-dark">{{ $jumlahSupplier }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-basket"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Transaksi Masuk Bulan Ini</p>
                                <h4 class="my-1 text-dark">{{ $jumlahTransaksiMasukBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-arrow-down-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Transaksi Keluar Bulan Ini</p>
                                <h4 class="my-1 text-dark">{{ $jumlahTransaksiKeluarBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-arrow-up-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row mt-3">
            <div class="col-12">
                <h5 class="text-info">Statistik Pesanan</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Pesanan Selesai (Desember)</p>
                                <h4 class="my-1 text-dark">5</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Jumlah Pesanan Proses (Desember)</p>
                                <h4 class="my-1 text-dark">5</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bx bx-time-five"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </main>
@endsection
