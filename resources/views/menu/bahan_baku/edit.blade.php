@extends('layout.main')
@section('content')
    <main class="page-content">
        <!-- Breadcrumb remains the same -->

        <div class="row ms-0 me-1">
            <div class="card radius-10 w-100">
                <div class="card-body">
                    <form action="{{ route(session()->get('role') . '.bahan_baku.update', $bahanBaku->bahan_baku_id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label class="form-label" for="kode_bahan_baku">Kode Bahan Baku</label>
                            <input type="text" id="kode_bahan_baku" name="kode_bahan_baku"
                                class="form-control @error('kode_bahan_baku') is-invalid @enderror"
                                value="{{ old('kode_bahan_baku', $bahanBaku->kode_bahan_baku) }}"
                                placeholder="Masukkan kode bahan baku">
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
                                value="{{ old('nama_bahan_baku', $bahanBaku->nama_bahan_baku) }}"
                                placeholder="Masukkan nama bahan baku">
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
                                <option value="Gram" {{ old('satuan', $bahanBaku->satuan) == 'Gram' ? 'selected' : '' }}>
                                    Gram
                                </option>
                                <option value="Ml" {{ old('satuan', $bahanBaku->satuan) == 'Ml' ? 'selected' : '' }}>
                                    Ml
                                </option>
                                <option value="Lainnya"
                                    {{ old('satuan', $bahanBaku->satuan) != 'Gram' && old('satuan', $bahanBaku->satuan) != 'Ml' ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                            <div id="other-satuan-container" class="mt-2"
                                style="display: {{ old('satuan', $bahanBaku->satuan) != 'Gram' && old('satuan', $bahanBaku->satuan) != 'Ml' ? 'block' : 'none' }};">
                                <input type="text" id="other_satuan" name="other_satuan"
                                    class="form-control @error('other_satuan') is-invalid @enderror"
                                    value="{{ old(
                                        'other_satuan',
                                        old('satuan', $bahanBaku->satuan) != 'Gram' && old('satuan', $bahanBaku->satuan) != 'Ml' ? $bahanBaku->satuan : '',
                                    ) }}"
                                    placeholder="Masukkan satuan lainnya">
                                @error('other_satuan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
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
                                value="{{ old('stok_minimal', $bahanBaku->stok_minimal) }}"
                                placeholder="Masukkan jumlah standarisasi">
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
            const satuan = document.getElementById('satuan');
            const otherSatuanContainer = document.getElementById('other-satuan-container');
            const otherSatuan = document.getElementById('other_satuan');

            // Handle satuan dropdown change
            satuan.addEventListener('change', function() {
                if (this.value === 'Lainnya') {
                    otherSatuanContainer.style.display = 'block';
                } else {
                    otherSatuanContainer.style.display = 'none';
                    otherSatuan.value = '';
                }
            });
        });
    </script>
@endsection
