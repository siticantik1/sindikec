<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Ruangan</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 5px; font-size: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        h4 { text-align: center; margin-bottom: 20px;}
        p { font-size: 12px; margin-bottom: 15px;}
    </style>
</head>
<body>
    <h4>KARTU INVENTARIS RUANGAN</h4>
    
    {{-- Menggunakan variabel $selectedRkl dan teks untuk Lengkongsari --}}
    <p><strong>RUANGAN:</strong> {{ $selectedRkl->name ?? 'SEMUA RUANGAN (KEL. LENGKONSARI)' }}</p>

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
                <th rowspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>B</th>
                <th>KB</th>
                <th>RB</th>
            </tr>
        </thead>
        <tbody>
            {{-- Menggunakan variabel $ikls untuk perulangan data --}}
            @forelse ($ikls as $item)
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
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data inventaris untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
