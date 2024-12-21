<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Bahan Baku</th>
                <th>Stok Sekarang</th>
                <th>Stok Minimum</th>
                <th>Prediksi Pembelian</th>
                <th>Harga Per Satuan</th>
                <th>Total</th>
                <th>Supplier Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                <tr class="table-danger">
                    <td>{{ $item->nama_bahan_baku }}</td>
                    <td>
                        {{ number_format($item->stok, 0, ',', '.') }}
                        {{ $item->satuan }}
                    </td>
                    <td>
                        {{ number_format($item->stok_minimal, 0, ',', '.') }}
                        {{ $item->satuan }}
                    </td>
                    <td>
                        <em><strong>{{ number_format($item->rata_rata, 0, ',', '.') }}
                                {{ $item->satuan }}</strong></em>
                    </td>
                    <td>
                        <em>
                            <strong>Rp.
                                {{ number_format($item->harga_per_satuan, 0, ',', '.') }}/{{ $item->satuan }}
                            </strong>
                        </em>
                    </td>
                    <td>
                        <em>
                            <strong>Rp.
                                {{ number_format($item->harga_per_satuan * $item->rata_rata, 0, ',', '.') }}
                            </strong>
                        </em>
                    </td>
                    <td><em><strong>{{ $item->supplier_terakhir }}</strong></em></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
