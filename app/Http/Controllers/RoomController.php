<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use PDF; // Pastikan barryvdh/laravel-dompdf sudah di-install

class RoomController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan.
     */
    public function index()
    {
        $rooms = Room::all();
        
        // Kode ini sudah benar untuk mengirim data ke konten utama halaman.
        // Data untuk sidebar (jika ada) sebaiknya di-handle oleh AppServiceProvider.
        return view('pages.room.index', [
            'rooms' => $rooms
        ]);
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function create()
    {
        return view('pages.room.create');
    }

    /**
     * Menyimpan data ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi sudah bagus
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100', 'unique:rooms,code'], // Saran: tambahkan unique agar kode tidak duplikat
        ]);

        Room::create($validatedData);

        return redirect('/room')->with('sukses', 'Berhasil menambahkan data ruangan.');
    }

    /**
     * Menampilkan form untuk mengedit data ruangan.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);

        return view('pages.room.edit', [
            'room' => $room,
        ]);
    }
    
    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:100'],
            'code' => ['required', 'max:100', 'unique:rooms,code,' . $id], // Saran: tambahkan unique dengan pengecualian ID saat ini
        ]);

        Room::findOrFail($id)->update($validatedData);

        return redirect('/room')->with('sukses', 'Berhasil mengubah data ruangan.');
    }
    
    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect('/room')->with('sukses', 'Berhasil menghapus data ruangan.');
    }

    /**
     * Membuat dan menampilkan laporan dalam format PDF.
     */
    public function printPDF()
    {
        $rooms = Room::all(); 
        
        // Menggunakan count() untuk menghitung total baris/data ruangan.
        $total_ruangan = $rooms->count();

        $pdf = PDF::loadView('pages.room.cetak', [
            'rooms' => $rooms,
            'total_ruangan' => $total_ruangan
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-data-ruangan.pdf');
    }
}