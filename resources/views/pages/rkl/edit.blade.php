@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- Judul diubah untuk RKL --}}
        <h1 class="h3 mb-0 text-gray-800">Edit Data Ruangan (Kel. Lengkongsari)</h1>
        {{-- Tombol kembali diarahkan ke 'lengkongsari.rkl.index' --}}
        <a href="{{ route('lengkongsari.rkl.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Ruangan</h6>
                </div>
                <div class="card-body">
                    {{-- 1. Arahkan form ke route 'lengkongsari.rkl.update' --}}
                    {{-- 2. Gunakan variabel $rkl yang dikirim dari RklController --}}
                    <form action="{{ route('lengkongsari.rkl.update', $rkl) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nama Ruangan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama ruangan..." value="{{ old('name', $rkl->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{-- ====================================================== --}}
                            {{-- PERBAIKAN UTAMA: Semua referensi 'code' diubah menjadi 'kode_ruangan' --}}
                            {{-- agar konsisten dengan Controller dan Model.               --}}
                            {{-- ====================================================== --}}
                            <label for="kode_ruangan">Kode Ruangan</label>
                            <input type="text" class="form-control @error('kode_ruangan') is-invalid @enderror" id="kode_ruangan" name="kode_ruangan" placeholder="Masukkan kode ruangan..." value="{{ old('kode_ruangan', $rkl->kode_ruangan) }}">
                            @error('kode_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Update Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

