<?php

namespace App\Http\Controllers;

use App\Models\Rkl;
use Illuminate\Http\Request;
use PDF; // Pastikan barryvdh/laravel-dompdf sudah di-install

class RklController extends Controller
{
   /**
     * Menampilkan daftar semua ruangan.
     */
    public function index()
    {
        $rkls = Rkl::all();
        
        // Kode ini sudah benar untuk mengirim data ke konten utama halaman.
        // Data untuk sidebar (jika ada) sebaiknya di-handle oleh AppServiceProvider.
        return view('pages.rkl.index', [
            'rkls' => $rkls
        ]);
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function create()
    {
        return view('pages.rkl.create');
    }

    /**
     * Menyimpan data ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi sudah bagus
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100', 'unique:rkls,code'], // Saran: tambahkan unique agar kode tidak duplikat
        ]);

        Rkl::create($validatedData);

        return redirect('/rkl')->with('sukses', 'Berhasil menambahkan data ruangan.');
    }

    /**
     * Menampilkan form untuk mengedit data ruangan.
     */
    public function edit($id)
    {
        $rkl = rkl::findOrFail($id);

        return view('pages.rkl.edit', [
            'rkl' => $rkl,
        ]);
    }
    
    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100', 'unique:rkl,code,' . $id], // Saran: tambahkan unique dengan pengecualian ID saat ini
        ]);

        Rkl::findOrFail($id)->update($validatedData);

        return redirect('/rkl')->with('sukses', 'Berhasil mengubah data ruangan.');
    }
    
    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy($id)
    {
        $rkl = Rkl::findOrFail($id);
        $rkl->delete();

        return redirect('/rkl')->with('sukses', 'Berhasil menghapus data ruangan.');
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF()
    {
        $rkls = Rkl::all(); 
        
        // Menggunakan count() untuk menghitung total baris/data ruangan.
        $total_ruangan = $rkls->count();

        $pdf = PDF::loadView('pages.room.cetak', [
            'rkls' => $rkls,
            'total_ruangan' => $total_ruangan
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-ruangan.pdf');
    }  
}
