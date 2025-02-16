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
            <div class="col-xl-4 col-md-6">
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
            <div class="col-xl-4 col-md-6">
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
            <div class="col-xl-4 col-md-6">
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

        <div class="row">
            <div class="col-xl-6 col-md-12">
                <div class="card radius-10 bg-purple-gradient">
                    <h5 class="card-header text-white bg-danger">Stok Bahan Baku Rendah</h5>
                    <div class="card-body">
                        <ul>
                            @forelse ($stokRendah as $item)
                                <li>
                                    <strong>{{ $item['nama_bahan_baku'] }}</strong><br>
                                    <small>Stok Saat Ini: {{ $item['stok_saat_ini'] }} {{ $item['satuan'] }}</small><br>
                                    <small>Standarisasi: {{ $item['stok_minimal'] }} {{ $item['satuan'] }}</small>
                                </li>
                            @empty
                                <li><strong>Tidak ada stok bahan baku rendah saat ini.</strong></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-12">
                <div class="card radius-10 bg-purple-gradient">
                    <h5 class="card-header text-white bg-warning">Stok Bahan Baku Kritis</h5>
                    <div class="card-body">
                        <ul>
                            @forelse ($stokKritis as $item)
                                <li>
                                    <strong>{{ $item['nama_bahan_baku'] }}</strong><br>
                                    <small>Stok Saat Ini: {{ $item['stok_saat_ini'] }} {{ $item['satuan'] }}</small><br>
                                    <small>Standarisasi: {{ $item['stok_minimal'] }} {{ $item['satuan'] }}</small>
                                </li>
                            @empty
                                <li><strong>Tidak ada stok bahan baku kritis saat ini.</strong></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top 5 Bahan Baku with the most purchases -->
            <div class="col-xl-6 col-md-12">
                <div class="card radius-10 bg-purple-gradient">
                    <h5 class="card-header text-white bg-info">Top 5 Bahan Baku dengan pembelian terbanyak</h5>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($topBahanBaku as $item)
                                <li class="list-group-item">
                                    <strong>{{ $item->bahanBaku->nama_bahan_baku }}</strong>
                                    <br>
                                    <small><strong>Total:</strong> {{ $item->total_pembelian }} kali pembelian</small>
                                    <br>
                                    <small><strong>Supplier:</strong>
                                        @foreach (explode(',', $item->supplier_ids) as $supplierId)
                                            {{ \App\Models\Supplier::find($supplierId)->nama_supplier }},
                                        @endforeach
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item"><strong>Tidak ada data bahan baku dengan pembelian
                                        terbanyak.</strong></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Top 5 Suppliers based on transaction frequency -->
            <div class="col-xl-6 col-md-12">
                <div class="card radius-10 bg-purple-gradient">
                    <h5 class="card-header text-white bg-info">Top 5 Supplier berdasarkan frekuensi transaksi</h5>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($topSupplier as $item)
                                <li class="list-group-item">
                                    <strong>{{ $item->supplier->nama_supplier }}</strong><br>
                                    <small><strong>Total:</strong> {{ $item->total_transaksi }} kali Pembelian</small>
                                </li>
                            @empty
                                <li class="list-group-item"><strong>Tidak ada data supplier berdasarkan frekuensi
                                        transaksi.</strong></li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
