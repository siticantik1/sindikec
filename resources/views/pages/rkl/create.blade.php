@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- Judul dibuat statis karena halaman ini khusus untuk Lengkongsari --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Data Ruangan (Lengkongsari)</h1>
        
        <a href="{{ route('lengkongsari.rkl.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Ruangan</h6>
                </div>
                <div class="card-body">
                    {{-- Menampilkan error validasi jika ada (dari kodemu, ini bagus!) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('lengkongsari.rkl.store') }}" method="POST">
                        @csrf

                        {{-- Input 'lokasi' yang tersembunyi sudah dihapus, karena controller baru menanganinya secara otomatis --}}

                        <div class="form-group">
                            <label for="name">Nama Ruangan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama ruangan..." value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kode_ruangan">Kode Ruangan</label>
                            
                            {{-- INI KUNCINYA: 'name' diubah dari 'code' menjadi 'kode_ruangan' agar cocok dengan Controller --}}
                            <input type="text" class="form-control @error('kode_ruangan') is-invalid @enderror" id="kode_ruangan" name="kode_ruangan" placeholder="Masukkan kode ruangan..." value="{{ old('kode_ruangan') }}" required>
                            
                            {{-- Kunci error juga disesuaikan menjadi 'kode_ruangan' --}}
                            @error('kode_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

