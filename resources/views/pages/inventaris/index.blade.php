{{-- Meng-extend layout utama --}}
@extends('layouts.app')

{{-- Bagian untuk judul halaman --}}
@section('title', 'Data Inventaris Kecamatan Tawang')

{{-- Bagian untuk konten utama --}}
@section('content')
<div class="container-fluid">
    
    {{-- Filter berdasarkan ruangan --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- PERBAIKAN: Form action diubah ke route inventaris Tawang --}}
            <form action="{{ route('tawang.inventaris.index') }}" method="GET">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="room_id_filter">Filter Berdasarkan Ruangan:</label>
                        {{-- PERBAIKAN: Nama select diubah menjadi 'room_id' --}}
                        <select name="room_id" id="room_id_filter" class="form-control">
                            <option value="">Tampilkan Semua</option>
                            {{-- PERBAIKAN: Looping menggunakan variabel $rooms --}}
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}" {{ request()->get('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
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
                <h4 class="m-0 font-weight-bold text-primary">Data Inventaris {{ $selectedRoom->name ?? 'Kec. Tawang' }}</h4>
                <div>
                    {{-- PERBAIKAN: Semua nama route diawali dengan 'tawang.' --}}
                    <a href="{{ route('tawang.inventaris.print', request()->query()) }}" class="btn btn-danger btn-icon-split btn-sm">
                        <span class="icon text-white-50"><i class="fas fa-file-print"></i></span>
                        <span class="text">Export PDF</span>
                    </a>
                    <a href="{{ route('tawang.inventaris.create') }}" class="btn btn-primary btn-icon-split btn-sm">
                        <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
                        <span class="text">Tambah Barang</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="inventaris-tawang-table">
                    <thead class="text-center" style="background-color: #f2f2f2;">
                        <tr>
                            <th rowspan="2" class="align-middle">No Urut</th>
                            <th rowspan="2" class="align-middle">Nama Barang</th>
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
                        {{-- PERBAIKAN: Variabel diubah menjadi $inventaris dan $item --}}
                        @forelse ($inventaris as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->merk_model ?? '-' }}</td>
                                <td>{{ $item->bahan ?? '-' }}</td>
                                <td class="text-center">{{ $item->tahun_pembelian }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td class="text-center">{{ $item->jumlah }}</td>
                                <td class="text-right">{{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                                
                                <td class="text-center">@if($item->kondisi == 'B') <i class="fas fa-check-circle text-success"></i> @endif</td>
                                <td class="text-center">@if($item->kondisi == 'KB') <i class="fas fa-exclamation-circle text-warning"></i> @endif</td>
                                <td class="text-center">@if($item->kondisi == 'RB') <i class="fas fa-times-circle text-danger"></i> @endif</td>
                                
                                <td>{{ $item->keterangan }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Aksi">
                                        <button type="button" class="btn btn-info btn-sm move-item-btn mr-1" 
                                                data-toggle="modal" 
                                                data-target="#moveItemModal" 
                                                data-id="{{ $item->id }}" 
                                                data-name="{{ $item->nama_barang }}"
                                                data-room="{{ $item->room->name ?? 'N/A' }}"
                                                title="Pindah Ruangan">
                                            <i class="fas fa-people-carry"></i>
                                        </button>

                                        <a href="{{ route('tawang.inventaris.edit', $item->id) }}" class="btn btn-warning btn-sm mr-1" title="Edit Barang">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form action="{{ route('tawang.inventaris.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" style="display:inline;">
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
                                    Tidak ada data inventaris ditemukan.
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
                <h5 class="modal-title" id="moveItemModalLabel">Pindahkan Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="moveItemForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Anda akan memindahkan barang: <strong id="itemName"></strong></p>
                    <p>Dari ruangan: <strong id="currentRoom"></strong></p>
                    <div class="form-group">
                        <label for="new_room_id">Pindahkan ke Ruangan:</label>
                        {{-- PERBAIKAN: Nama input dan loop disesuaikan --}}
                        <select class="form-control" name="new_room_id" required>
                            <option value="">-- Pilih Ruangan Tujuan --</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
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
    $('#moveItemModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var itemId = button.data('id');
        var itemName = button.data('name');
        var currentRoom = button.data('room');
        
        // ======================================================
        // PERBAIKAN UTAMA: URL dan Parameter disesuaikan 100% dengan web.php
        // ======================================================
        let formAction = "{{ route('tawang.inventaris.move', ['inventaris' => ':id']) }}";
        formAction = formAction.replace(':id', itemId);
        
        var modal = $(this);
        modal.find('#moveItemForm').attr('action', formAction);
        modal.find('#itemName').text(itemName);
        modal.find('#currentRoom').text(currentRoom); // ID disesuaikan
    });
});
</script>
@endpush


