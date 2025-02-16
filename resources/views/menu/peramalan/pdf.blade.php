<!DOCTYPE html>
<html>

<head>
    <title>{{ $title ?? 'Laporan Prediksi' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-muted {
            color: #858796;
        }

        .text-danger {
            color: #dc3545;
        }

        .table-danger {
            background-color: #f8d7da;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #6c757d;
        }

        small {
            font-size: 85%;
        }

        .signature {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title ?? 'Laporan Prediksi' }}</h1>
        <p style="font-size: 13px">Bulan prediksi : {{ $bulan_prediksi ?? '-' }}</p>
    </div>

    @php
        $aturanBahanBaku = [
            [
                'nama_bahan_baku' => 'Amidis',
                'satuan' => 'Gram',
                'pcs' => 'Galon',
                'satuanPerPcs' => 19000,
                'harga' => 22000,
            ],
            [
                'nama_bahan_baku' => 'Biotin',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 250,
                'harga' => 1663750,
            ],
            [
                'nama_bahan_baku' => 'Musk Fragrance',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 500,
                'harga' => 600000,
            ],
            [
                'nama_bahan_baku' => 'Jojoba Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 2100000,
            ],
            [
                'nama_bahan_baku' => 'Bakuchiol Ekstrak',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 4120000,
            ],
            [
                'nama_bahan_baku' => 'Pewarna Hijau',
                'satuan' => 'Gram',
                'pcs' => 'Botol',
                'satuanPerPcs' => 10,
                'harga' => 5000,
            ],
            [
                'nama_bahan_baku' => 'Charcoal',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 250,
                'harga' => 40000,
            ],
            [
                'nama_bahan_baku' => 'Coconut Oil',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 18000,
                'harga' => 24000,
            ],
            [
                'nama_bahan_baku' => 'Olive Oil',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 20000,
                'harga' => 57000,
            ],
            [
                'nama_bahan_baku' => 'Castor Oil',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 1000,
                'harga' => 60000,
            ],
            [
                'nama_bahan_baku' => 'Glycerin',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 26000,
            ],
            [
                'nama_bahan_baku' => 'Koh',
                'satuan' => 'Gram',
                'pcs' => 'Karung',
                'satuanPerPcs' => 25000,
                'harga' => 30000,
            ],
            [
                'nama_bahan_baku' => 'Natrosol',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 220000,
            ],
            [
                'nama_bahan_baku' => 'Madu',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 7000,
                'harga' => 53500,
            ],
            [
                'nama_bahan_baku' => 'Shea Butter',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 215000,
            ],
            [
                'nama_bahan_baku' => 'Calendula',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 500,
                'harga' => 1042000,
            ],
            [
                'nama_bahan_baku' => 'Daun Bidara',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 55000,
            ],
            [
                'nama_bahan_baku' => 'Daun The Hijau',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 64000,
            ],
            [
                'nama_bahan_baku' => 'Alcohol',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 1000,
                'harga' => 13000,
            ],
            [
                'nama_bahan_baku' => 'Tea Tree Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 1300000,
            ],
            [
                'nama_bahan_baku' => 'Eucalyptus Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 716000,
            ],
            [
                'nama_bahan_baku' => 'Blackseed Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 235000,
            ],
            [
                'nama_bahan_baku' => 'Centela Asiatica',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 400680,
            ],
            [
                'nama_bahan_baku' => 'Spearmint',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 1411000,
            ],
            [
                'nama_bahan_baku' => 'Roman Chamomile',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 1985500,
            ],
            [
                'nama_bahan_baku' => 'Lavender Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 1358500,
            ],
            [
                'nama_bahan_baku' => 'Yuzu Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 1915000,
            ],
            [
                'nama_bahan_baku' => 'Licorice Ekstrak',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 888000,
            ],
            [
                'nama_bahan_baku' => 'Vanila Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 2769000,
            ],
            [
                'nama_bahan_baku' => 'Green Tea Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 1000,
                'harga' => 2500000,
            ],
            [
                'nama_bahan_baku' => 'Coco Glucoside',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 5000,
                'harga' => 77000,
            ],
            [
                'nama_bahan_baku' => 'Decyl Glucoside',
                'satuan' => 'Gram',
                'pcs' => 'Jerigen',
                'satuanPerPcs' => 5000,
                'harga' => 78000,
            ],
            [
                'nama_bahan_baku' => 'Kukui Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => 250,
                'harga' => 25000,
            ],
            [
                'nama_bahan_baku' => 'Ginger Oil',
                'satuan' => 'Ml',
                'pcs' => 'Botol',
                'satuanPerPcs' => '1000 ml',
                'harga' => 1570000,
            ],
            [
                'nama_bahan_baku' => 'SMCT',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 270000,
            ],
            [
                'nama_bahan_baku' => 'Cocoyl Ishethionate',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 330000,
            ],
            [
                'nama_bahan_baku' => 'Lauroyl Sarcosinate',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 170000,
            ],
            [
                'nama_bahan_baku' => 'Wheat Protein',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 1200000,
            ],
            [
                'nama_bahan_baku' => 'Coco Betain',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 1000,
                'harga' => 100000,
            ],
            [
                'nama_bahan_baku' => 'Citric Acid',
                'satuan' => 'Gram',
                'pcs' => 'Plastik',
                'satuanPerPcs' => 100,
                'harga' => 5000,
            ],
        ];
    @endphp

    @if (isset($bahan_baku) && count($bahan_baku) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center">No.</th>
                    <th>Bahan Baku</th>
                    <th>Prediksi Pembelian</th>
                    <th>Informasi Pembelian Terakhir</th>
                    <th>Total Harga Prediksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_biaya = 0;
                    $no = 1;
                @endphp
                @foreach ($bahan_baku as $data)
                    @php
                        $matchingBahanBaku = collect($aturanBahanBaku)->firstWhere(
                            'nama_bahan_baku',
                            $data['nama_bahan_baku'] ?? '',
                        );

                        $prediksi = isset($data['prediksi_bulan_selanjutnya']['jumlah_keluar'])
                            ? floatval($data['prediksi_bulan_selanjutnya']['jumlah_keluar'])
                            : 0;

                        if ($matchingBahanBaku && $prediksi > 0 && is_numeric($matchingBahanBaku['satuanPerPcs'])) {
                            $satuanPerPcs = floatval($matchingBahanBaku['satuanPerPcs']);
                            $jumlahPembulatan = ceil($prediksi / $satuanPerPcs);
                            $biaya = $matchingBahanBaku['harga'] * $jumlahPembulatan;
                        } else {
                            $harga = isset($data['supplier_terakhir']['harga_per_satuan'])
                                ? $data['supplier_terakhir']['harga_per_satuan']
                                : 0;
                            $biaya = is_numeric($harga) ? $harga * $prediksi : 0;
                        }

                        $total_biaya += $biaya;
                    @endphp
                    <tr>
                        <td style="text-align: center">{{ $no++ }}.</td>

                        <td>
                            <span>
                                {{ $data['nama_bahan_baku'] ?? 'Tidak ada nama' }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $data['kode_bahan_baku'] ?? 'Tidak ada kode' }}</small>
                        </td>

                        <td>
                            @if (isset($data['prediksi_bulan_selanjutnya']['jumlah_keluar']))
                                {{-- Tampilkan jumlah prediksi dan satuan --}}
                                {{ number_format($data['prediksi_bulan_selanjutnya']['jumlah_keluar'], 0, ',', '.') }}
                                {{ $data['satuan'] ?? '-' }}

                                {{-- Cari data bahan baku yang sesuai --}}
                                @php
                                    $matchingBahanBaku = collect($aturanBahanBaku)->firstWhere(
                                        'nama_bahan_baku',
                                        $data['nama_bahan_baku'] ?? '',
                                    );

                                    $prediksi = floatval($data['prediksi_bulan_selanjutnya']['jumlah_keluar']);
                                @endphp

                                {{-- Tampilkan informasi pembulatan jika ada data yang cocok dan prediksi > 0 --}}
                                @if ($matchingBahanBaku && $prediksi > 0 && is_numeric($matchingBahanBaku['satuanPerPcs']))
                                    @php
                                        $satuanPerPcs = floatval($matchingBahanBaku['satuanPerPcs']);
                                        $jumlahPembulatan = ceil($prediksi / $satuanPerPcs);
                                    @endphp
                                    <br>
                                    <small>
                                        <em>Pembulatan: {{ $jumlahPembulatan }} {{ $matchingBahanBaku['pcs'] }}</em>
                                    </small>
                                @endif
                            @else
                                <span class="text-muted">Prediksi tidak tersedia</span>
                            @endif
                        </td>

                        <td>
                            @if ($matchingBahanBaku && $data['prediksi_bulan_selanjutnya']['jumlah_keluar'] > 0)
                                Harga per {{ $matchingBahanBaku['pcs'] }} : <strong>Rp
                                    {{ number_format($matchingBahanBaku['harga'], 0, ',', '.') }}</strong>
                                <br>
                                @if (isset($data['supplier_terakhir']['harga_per_satuan']) && isset($data['supplier_terakhir']['nama_supplier']))
                                    Supplier : <strong>{{ $data['supplier_terakhir']['nama_supplier'] }}</strong>
                                    <br>
                                @endif
                                <small class="text-muted">Harga sesuai aturan bahan baku</small>
                            @elseif (isset($data['supplier_terakhir']['harga_per_satuan']) &&
                                    isset($data['supplier_terakhir']['nama_supplier']) &&
                                    $data['prediksi_bulan_selanjutnya']['jumlah_keluar'] > 0)
                                Harga per {{ $data['satuan'] ?? '-' }} : <strong>Rp
                                    {{ number_format($data['supplier_terakhir']['harga_per_satuan'], 0, ',', '.') }}</strong>
                                <br>
                                Supplier : <strong>{{ $data['supplier_terakhir']['nama_supplier'] }}</strong>
                            @elseif ($data['prediksi_bulan_selanjutnya']['jumlah_keluar'] == 0)
                                <span class="text-muted">Tidak tersedia karena jumlah prediksi 0</span>
                            @else
                                <span class="text-muted">Informasi belum tersedia</span>
                            @endif
                        </td>

                        <td>
                            @if ($biaya > 0)
                                Rp {{ number_format($biaya, 0, ',', '.') }}
                            @else
                                <span class="text-muted">Informasi biaya belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                @endforeach

                <tr style="background-color:#f2f2f2">
                    <td colspan="4" class="fw-bold" style="text-align: right">
                        Total Seluruh Harga:
                    </td>
                    <td class="fw-bold">Rp {{ number_format($total_biaya, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $tanggal = '01 ' . $bulan_prediksi;
        @endphp
        <div class="signature" style="margin-top: 80px">
            <p>{{ $tanggal }}</p>
            <p>Dibuat oleh {{ $nama_pembuat ?? '-' }}</p>
        </div>
    @else
        <div class="no-data">
            <p>Tidak ada data bahan baku yang tersedia</p>
        </div>
    @endif
</body>

</html>
