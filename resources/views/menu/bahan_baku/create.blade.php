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
                            <a href="<?= route(session()->get('role') . '.dashboard') ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= route(session()->get('role') . '.bahan_baku.index') ?>">Bahan Baku</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">
                            <span class="text-dark">Tambah Bahan Baku</span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route(session()->get('role') . '.bahan_baku.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="form-label" for="kode_bahan_baku">Kode Bahan Baku</label>

                            <input type="text" id="kode_bahan_baku" name="kode_bahan_baku"
                                class="form-control @error('kode_bahan_baku') is-invalid @enderror"
                                value="{{ old('kode_bahan_baku') }}" placeholder="Kode akan digenerate otomatis">
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_generate" checked>
                                    <label class="form-check-label" for="auto_generate">
                                        Generate Otomatis
                                    </label>
                                </div>
                            </div>
                            @error('kode_bahan_baku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="nama_bahan_baku">Nama Bahan Baku</label>
                            <input type="text" id="nama_bahan_baku" name="nama_bahan_baku"
                                class="form-control @error('nama_bahan_baku') is-invalid @enderror"
                                value="{{ old('nama_bahan_baku') }}" placeholder="Masukkan nama bahan baku">
                            @error('nama_bahan_baku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <select name="satuan" id="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                <option value="">Pilih satuan</option>
                                <option value="Liter" {{ old('satuan') == 'Liter' ? 'selected' : '' }}>
                                    Liter
                                </option>
                                <option value="Kilogram" {{ old('satuan') == 'Kilogram' ? 'selected' : '' }}>
                                    Kilogram
                                </option>
                            </select>
                            @error('satuan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="stok_minimal">Standarisasi</label>
                            <input type="number" min="1" id="stok_minimal" name="stok_minimal"
                                class="form-control @error('stok_minimal') is-invalid @enderror"
                                value="{{ old('stok_minimal') }}" placeholder="Masukkan jumlah standarisasi">
                            @error('stok_minimal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-end mb-3 mt-4">
                            <a href="{{ route(session()->get('role') . '.bahan_baku.index') }}"
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
        document.addEventListener('DOMContentLoaded', function() {
            const kodeBahanBaku = document.getElementById('kode_bahan_baku');
            const namaBahanBaku = document.getElementById('nama_bahan_baku');
            const autoGenerate = document.getElementById('auto_generate');
            const lastId = {{ $lastId }};

            function generateKode(nama) {
                if (!nama) return `BB-000-${String(lastId).padStart(3, '0')}`;

                // Split nama into words
                const words = nama.trim().split(/\s+/);
                let kodeNama = '';

                // Get first two letters of each word, or first letter if word is single character
                words.forEach(word => {
                    if (word.length >= 2) {
                        kodeNama += word.substring(0, 2);
                    } else {
                        kodeNama += word.substring(0, 1);
                    }
                });

                // Generate final kode: BB-KODE-ID
                const idPadded = String(lastId).padStart(3, '0');
                return `BB-${kodeNama.toUpperCase()}-${idPadded}`;
            }

            // Set initial state
            kodeBahanBaku.readOnly = autoGenerate.checked;

            // Handle nama bahan baku input
            namaBahanBaku.addEventListener('input', function() {
                if (autoGenerate.checked) {
                    kodeBahanBaku.value = generateKode(this.value);
                }
            });

            // Handle checkbox change
            autoGenerate.addEventListener('change', function() {
                kodeBahanBaku.readOnly = this.checked;
                if (this.checked && namaBahanBaku.value) {
                    kodeBahanBaku.value = generateKode(namaBahanBaku.value);
                }
            });
        });
    </script>
@endsection
