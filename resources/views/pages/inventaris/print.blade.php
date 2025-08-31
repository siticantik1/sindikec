<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris</title>
    <style>
        body { font-family: sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        .text-center { text-align: center; }
        h4 { text-align: center; }
    </style>
</head>
<body>
    <h4>KARTU INVENTARIS RUANGAN</h4>
    <p><strong>RUANGAN:</strong> {{ $selectedRoom->nama_ruangan ?? 'SEMUA RUANGAN' }}</p>

    <table class="table">
        <thead class="text-center">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Barang</th>
                <th rowspan="2">Merk/Model</th>
                <th rowspan="2">Tahun</th>
                <th rowspan="2">Kode Barang</th>
                <th rowspan="2">Jumlah</th>
                <th rowspan="2">Harga (Rp)</th>
                <th colspan="3">Keadaan Barang</th>
            </tr>
            <tr>
                <th>B</th>
                <th>KB</th>
                <th>RB</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventaris as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->merk_model ?? '-' }}</td>
                    <td class="text-center">{{ $item->tahun_pembelian }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                    <td class="text-center">@if($item->kondisi == 'B') ✓ @endif</td>
                    <td class="text-center">@if($item->kondisi == 'KB') ✓ @endif</td>
                    <td class="text-center">@if($item->kondisi == 'RB') ✓ @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>