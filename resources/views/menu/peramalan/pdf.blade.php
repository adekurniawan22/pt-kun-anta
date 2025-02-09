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
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title ?? 'Laporan Prediksi' }}</h1>
        <p style="font-size: 13px">Bulan prediksi : {{ $bulan_prediksi ?? '-' }}</p>
    </div>

    @if (isset($bahan_baku) && count($bahan_baku) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center">No.</th>
                    <th>Bahan Baku</th>
                    <th>Prediksi Pembelian</th>
                    <th>Informasi Pembelian Terakhir</th>
                    <th>Total Prediksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_biaya = 0;
                    $no = 1;
                @endphp
                @foreach ($bahan_baku as $data)
                    @php
                        $biaya =
                            isset($data['supplier_terakhir']['harga_per_satuan']) &&
                            is_numeric($data['supplier_terakhir']['harga_per_satuan']) &&
                            isset($data['prediksi_bulan_selanjutnya']['jumlah_keluar'])
                                ? $data['supplier_terakhir']['harga_per_satuan'] *
                                    $data['prediksi_bulan_selanjutnya']['jumlah_keluar']
                                : 0;
                        $total_biaya += $biaya;
                    @endphp
                    <tr>
                        <td style="text-align: center">{{ $no++ }}.</td>
                        <td>
                            <span>{{ $data['nama_bahan_baku'] ?? 'Tidak ada nama' }}</span>
                            <br>
                            <small class="text-muted">{{ $data['kode_bahan_baku'] ?? 'Tidak ada kode' }}</small>
                        </td>

                        <td>
                            @if (isset($data['prediksi_bulan_selanjutnya']['jumlah_keluar']))
                                {{ number_format($data['prediksi_bulan_selanjutnya']['jumlah_keluar'], 0, ',', '.') }}
                                <small>{{ $data['satuan'] ?? '-' }}</small>
                            @else
                                <span class="text-muted">Tidak ada prediksi</span>
                            @endif
                        </td>
                        <td>
                            @if (isset($data['supplier_terakhir']['harga_per_satuan']) && isset($data['supplier_terakhir']['nama_supplier']))
                                Harga per {{ $data['satuan'] ?? '-' }} : <strong>Rp
                                    {{ number_format($data['supplier_terakhir']['harga_per_satuan'], 0, ',', '.') }}</strong>
                                <br>
                                Supplier : <strong>{{ $data['supplier_terakhir']['nama_supplier'] }}</strong>
                            @else
                                <span class="text-muted">Belum ada info tersedia</span>
                            @endif
                        </td>
                        <td>
                            @if ($biaya > 0)
                                Rp {{ number_format($biaya, 0, ',', '.') }}
                            @else
                                <span class="text-muted">Harga belum tersedia</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color:#f2f2f2">
                    <td colspan="4" class="fw-bold" style="text-align: right">
                        Total Biaya:
                    </td>
                    <td class="fw-bold">Rp {{ number_format($total_biaya, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data bahan baku yang tersedia</p>
        </div>
    @endif
</body>

</html>
