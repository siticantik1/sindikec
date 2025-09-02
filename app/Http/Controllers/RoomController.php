<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar ruangan HANYA untuk Tawang.
     */
    public function index()
    {
        $rooms = Room::where('lokasi', 'tawang')->orderBy('name')->get();
        return view('pages.room.index', compact('rooms'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru untuk Tawang.
     */
    public function create()
    {
        return view('pages.room.create');
    }

    /**
     * Menyimpan ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // ======================================================
        // PERBAIKAN: Validasi disesuaikan menjadi 'kode_ruangan' agar cocok dengan Model
        // ======================================================
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode_ruangan' => 'required|string|max:50|unique:rooms,kode_ruangan',
        ]);

        // Lokasi diatur otomatis di sini
        $validatedData['lokasi'] = 'tawang';

        Room::create($validatedData);

        return redirect()->route('tawang.room.index')
                         ->with('success', 'Ruangan baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit ruangan.
     */
    public function edit(Room $room)
    {
        return view('pages.room.edit', compact('room'));
    }

    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, Room $room)
    {
        // ======================================================
        // PERBAIKAN UTAMA: Validasi disesuaikan menjadi 'kode_ruangan' agar cocok dengan Model
        // ======================================================
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode_ruangan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms', 'kode_ruangan')->ignore($room->id),
            ],
        ]);

        $room->update($validatedData);

        return redirect()->route('tawang.room.index')
                         ->with('success', 'Data ruangan berhasil diperbarui!');
    }

    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('tawang.room.index')
                         ->with('success', 'Ruangan berhasil dihapus.');
    }
    
    /**
     * Fungsi untuk mencetak PDF ruangan Tawang.
     */
    public function pdf()
    {
        // Logika untuk membuat PDF bisa ditambahkan di sini
        $rooms = Room::where('lokasi', 'tawang')->orderBy('name')->get();
        // Contoh: return PDF::loadView('pages.room.pdf', compact('rooms'))->download('laporan-ruangan-tawang.pdf');
        
        return "Halaman PDF untuk Ruangan Tawang";
    }
}

