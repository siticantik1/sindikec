@extends('layouts.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        {{-- PERBAIKAN: Judul dibuat statis untuk Tawang agar tidak butuh variabel $lokasi --}}
        <h1 class="h3 mb-0 text-gray-800">Tambah Ruangan Baru (Kec. Tawang)</h1>
        
        {{-- PERBAIKAN: Tombol kembali diarahkan ke route yang benar ('tawang.room.index') --}}
        <a href="{{ route('tawang.room.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
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
                    {{-- ====================================================== --}}
                    {{-- PERBAIKAN UTAMA: Action form diarahkan ke 'tawang.room.store' --}}
                    {{-- ====================================================== --}}
                    <form action="{{ route('tawang.room.store') }}" method="POST">
                        @csrf

                        {{-- Input 'lokasi' yang tersembunyi dihapus karena tidak diperlukan lagi --}}

                        <div class="form-group">
                            <label for="name">Nama Ruangan</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama ruangan..." value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kode_ruangan">Kode Ruangan</label>
                            {{-- PERBAIKAN: Nama input diubah dari 'code' menjadi 'kode_ruangan' agar konsisten --}}
                            <input type="text" class="form-control @error('kode_ruangan') is-invalid @enderror" id="kode_ruangan" name="kode_ruangan" placeholder="Masukkan kode unik ruangan..." value="{{ old('kode_ruangan') }}" required>
                            @error('kode_ruangan')
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

