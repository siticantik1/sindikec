{{-- Meng-extend layout utama, disesuaikan dengan contoh Anda --}}
@extends('layouts.app')

{{-- Bagian untuk judul halaman --}}
@section('title', 'Data IKL Kelurahan Lengkongsari')

{{-- Bagian untuk konten utama --}}
@section('content')
<div class="container-fluid">
    
    {{-- DITAMBAHKAN: Filter berdasarkan ruangan untuk user experience yang lebih baik --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('lengkongsari.ikl.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="rkl_id_filter">Filter Berdasarkan Ruangan:</label>
                        <select name="rkl_id" id="rkl_id_filter" class="form-control">
                            <option value="">Tampilkan Semua</option>
                            @foreach ($rkls as $rkl)
                                {{-- Gunakan request()->get('rkl_id') untuk mempertahankan filter yang dipilih --}}
                                <option value="{{ $rkl->id }}" {{ request()->get('rkl_id') == $rkl->id ? 'selected' : '' }}>
                                    {{ $rkl->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="m-0 font-weight-bold text-primary">Data IKL {{ $selectedRkl->name ?? '' }} - Kel. Lengkongsari</h4>
                <div>
                    <a href="{{ route('lengkongsari.ikl.print', request()->query()) }}" class="btn btn-danger btn-icon-split btn-sm">
                        <span class="icon text-white-50"><i class="fas fa-file-pdf"></i></span>
                        <span class="text">Export PDF</span>
                    </a>
                    <a href="{{ route('lengkongsari.ikl.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                        <span class="text">Tambah Barang</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            {{-- DITAMBAHKAN: Menampilkan pesan sukses setelah operasi CRUD --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="ikl-lengkongsari-table">
                    <thead class="text-center" style="background-color: #f2f2f2;">
                        <tr>
                            <th rowspan="2" class="align-middle">No Urut</th>
                            <th rowspan="2" class="align-middle">Nama Barang / Jenis Barang</th>
                            <th rowspan="2" class="align-middle">Merk / Model</th>
                            <th rowspan="2" class="align-middle">Bahan</th>
                            <th rowspan="2" class="align-middle">Tahun Pembelian</th>
                            <th rowspan="2" class="align-middle">No. Kode Barang</th>
                            <th rowspan="2" class="align-middle">Jumlah</th>
                            <th rowspan="2" class="align-middle">Harga (Rp)</th>
                            <th colspan="3">Keadaan Barang</th>
                            <th rowspan="2" class="align-middle">Keterangan</th>
                            <th rowspan="2" class="align-middle">Aksi</th>
                        </tr>
                        <tr>
                            <th>Baik (B)</th>
                            <th>Kurang Baik (KB)</th>
                            <th>Rusak Berat (RB)</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DIPERBAIKI: Variabel disesuaikan menjadi $ikls dan $ikl agar cocok dengan Controller --}}
                        @forelse ($ikls as $ikl)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $ikl->nama_barang }}</td>
                                <td>{{ $ikl->merk_model ?? '-' }}</td>
                                <td>{{ $ikl->bahan ?? '-' }}</td>
                                <td class="text-center">{{ $ikl->tahun_pembelian }}</td>
                                <td>{{ $ikl->kode_barang }}</td>
                                <td class="text-center">{{ $ikl->jumlah }}</td>
                                <td class="text-right">{{ number_format($ikl->harga_perolehan, 0, ',', '.') }}</td>
                                
                                <td class="text-center">@if($ikl->kondisi == 'B') <i class="fas fa-check-circle text-success"></i> @endif</td>
                                <td class="text-center">@if($ikl->kondisi == 'KB') <i class="fas fa-exclamation-circle text-warning"></i> @endif</td>
                                <td class="text-center">@if($ikl->kondisi == 'RB') <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                
                                <td>{{ $ikl->keterangan }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm move-item-btn mr-1" 
                                                data-toggle="modal" 
                                                data-target="#moveItemModal" 
                                                data-id="{{ $ikl->id }}" 
                                                data-name="{{ $ikl->nama_barang }}"
                                                data-rkl="{{ $ikl->rkl->name ?? 'N/A' }}"
                                                title="Pindah Ruangan">
                                            <i class="fas fa-people-carry"></i>
                                        </button>

                                        <a href="{{ route('lengkongsari.ikl.edit', $ikl->id) }}" class="btn btn-warning btn-sm mr-1" title="Edit Barang">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form action="{{ route('lengkongsari.ikl.destroy', $ikl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Barang">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">
                                    Tidak ada data inventaris IKL ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pindah Ruangan -->
<div class="modal fade" id="moveItemModal" tabindex="-1" role="dialog" aria-labelledby="moveItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveItemModalLabel">Pindahkan Barang IKL</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="moveItemForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Anda akan memindahkan barang: <strong id="itemName"></strong></p>
                    <p>Dari ruangan: <strong id="currentRkl"></strong></p>
                    <div class="form-group">
                        <label for="new_rkl_id">Pindahkan ke Ruangan:</label>
                        <select class="form-control" id="new_rkl_id" name="new_rkl_id" required>
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            @foreach ($rkls as $rkl)
                                <option value="{{ $rkl->id }}">{{ $rkl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Pindahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.move-item-btn').on('click', function() {
        const itemId = $(this).data('id');
        const itemName = $(this).data('name');
        const currentRkl = $(this).data('rkl');
        
        // DIPERBAIKI: Ini adalah perbaikan bug paling penting.
        // URL sekarang dibuat menggunakan nama route Laravel yang benar,
        // sehingga akan selalu benar meskipun ada perubahan struktur URL.
        let formAction = "{{ route('lengkongsari.ikl.move', ['ikl' => ':id']) }}";
        formAction = formAction.replace(':id', itemId);
        
        $('#moveItemForm').attr('action', formAction);
        $('#itemName').text(itemName);
        $('#currentRkl').text(currentRkl);
    });
});
</script>
@endpush
