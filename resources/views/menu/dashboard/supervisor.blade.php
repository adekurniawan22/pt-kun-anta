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

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card radius-10 bg-gradient-cosmic">
                    <div class="card-body">
                        <div class="text-start">
                            <h2 class="text-dark">Selamat Datang di Dashboard Supervisor</h2>
                            <p class="text-dark mb-4">
                                Anda adalah pemantau utama dari sistem kami. Di sini, Anda dapat mengatur semua transaksi
                                yang
                                masuk dan keluar untuk memastikan
                                kelancaran operasional.
                            </p>
                            <a href="{{ route('supervisor.transaksi.index') }}" class="btn btn-success">
                                <i class="bx bx-package"></i> Kelola Transaksi Bahan Baku
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Penggunaan Bahan Baku Masuk Bulan Ini</p>
                                <h4 class="my-1 text-dark">{{ $jumlahTransaksiMasukBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-arrow-down-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card radius-10 bg-purple-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-dark">Penggunaan Bahan Baku Keluar Bulan Ini</p>
                                <h4 class="my-1 text-dark">{{ $jumlahTransaksiKeluarBulanIni }}</h4>
                            </div>
                            <div class="text-dark ms-auto font-35"><i class="bi bi-arrow-up-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
