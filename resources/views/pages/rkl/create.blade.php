@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- DIUBAH: Judul diubah menjadi dinamis dan spesifik untuk RKL --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Data RKL ({{ ucfirst($lokasi) }})</h1>
        
        {{-- DIUBAH: Tombol kembali diarahkan ke route index RKL Lengkongsari yang benar --}}
        <a href="{{ route('lengkongsari.rkl.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    {{-- DIUBAH: Judul form disesuaikan --}}
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah RKL</h6>
                </div>
                <div class="card-body">
                    {{-- DIUBAH: Arahkan form ke route store RKL Lengkongsari yang benar --}}
                    <form action="{{ route('lengkongsari.rkl.store') }}" method="POST">
                        @csrf

                        {{-- DITAMBAHKAN: Ini adalah baris kunci yang memperbaiki error --}}
                        {{-- Input tersembunyi ini mengirimkan info lokasi ke controller --}}
                        <input type="hidden" name="lokasi" value="{{ $lokasi }}">

                        <div class="form-group">
                            {{-- DIUBAH: Label disesuaikan --}}
                            <label for="name">Nama RKL</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama RKL..." value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{-- DIUBAH: Label disesuaikan --}}
                            <label for="code">Kode RKL</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Masukkan kode RKL..." value="{{ old('code') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

