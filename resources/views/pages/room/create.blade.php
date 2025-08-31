@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- DIUBAH: Judul dibuat dinamis untuk menunjukkan lokasi --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Ruangan Baru ({{ ucfirst($lokasi) }})</h1>
        
        {{-- Tombol untuk kembali ke halaman sebelumnya --}}
        <a href="{{ url()->previous() }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Ruangan</h6>
                </div>
                <div class="card-body">
                    {{-- Arahkan form ke route 'room.store' dengan method POST --}}
                    <form action="{{ route('room.store') }}" method="POST">
                        {{-- Token CSRF untuk keamanan --}}
                        @csrf

                        {{-- DITAMBAHKAN: INI ADALAH BAGIAN PALING PENTING --}}
                        {{-- Input tersembunyi ini akan mengirimkan info lokasi ke controller saat form disubmit --}}
                        <input type="hidden" name="lokasi" value="{{ $lokasi }}">

                        <div class="form-group">
                            <label for="name">Nama Ruangan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama ruangan..." value="{{ old('name') }}" required>
                            {{-- Menampilkan pesan error jika validasi 'name' gagal --}}
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="code">Kode Ruangan</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Masukkan kode ruangan..." value="{{ old('code') }}" required>
                             {{-- Menampilkan pesan error jika validasi 'code' gagal --}}
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

