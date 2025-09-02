@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Ruangan Kelurahan Lengkongsari</h1>
        <div>
            {{-- ====================================================== --}}
            {{-- PERBAIKAN: Nama route disesuaikan dengan web.php --}}
            {{-- ====================================================== --}}
            <a href="{{ route('lengkongsari.rkl.pdf') }}" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
            </a>
            
            {{-- PERBAIKAN: Nama route disesuaikan dengan web.php --}}
            <a href="{{ route('lengkongsari.rkl.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
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
                                @forelse ($rkls as $rkl)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rkl->name }}</td>
                                    {{-- PERBAIKAN: Diubah dari 'code' menjadi 'kode_ruangan' agar cocok dengan database --}}
                                    <td>{{ $rkl->kode_ruangan }}</td>
                                    <td>
                                        <div class="d-flex">
                                            {{-- PERBAIKAN: Nama route disesuaikan dengan web.php --}}
                                            <a href="{{ route('lengkongsari.rkl.edit', $rkl->id) }}" class="d-inline-block mr-2 btn btn-sm btn-warning">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            
                                            {{-- Tombol Hapus (pastikan form di dalam modal juga menggunakan route 'lengkongsari.rkl.destroy') --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmationDelete-{{ $rkl->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr> 
                                {{-- Pastikan di dalam file 'confirmation-delete' route-nya juga sudah benar --}}
                                @include('pages.rkl.confirmation-delete', ['rkl' => $rkl])
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
