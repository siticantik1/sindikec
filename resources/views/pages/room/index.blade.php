@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Ruangan Kecamatan Tawang</h1>
        <div>
            {{-- ====================================================== --}}
            {{-- PERBAIKAN: Semua nama route disesuaikan dengan web.php --}}
            {{-- ====================================================== --}}
            <a href="{{ route('tawang.room.pdf') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
            </a>
            
            <a href="{{ route('tawang.room.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hovered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruangan</th>
                                    <th>Kode Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $room->name }}</td>
                                    {{-- PERBAIKAN: Diubah dari 'code' menjadi 'kode_ruangan' agar cocok dengan database --}}
                                    <td>{{ $room->kode_ruangan }}</td>
                                    <td>
                                        <div class="d-flex">
                                            {{-- PERBAIKAN: Nama route disesuaikan dengan web.php --}}
                                            <a href="{{ route('tawang.room.edit', $room->id) }}" class="d-inline-block mr-2 btn btn-sm btn-warning">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            
                                            {{-- PERBAIKAN: Form untuk hapus juga disesuaikan route-nya --}}
                                            <form action="{{ route('tawang.room.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ruangan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr> 
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="pt-3">Tidak Ada Data</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

