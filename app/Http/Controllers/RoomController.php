<?php

namespace App\Http\Controllers;

use App\Models\Room; // Pastikan model Room di-import
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar ruangan sesuai lokasi (Tawang/Lengkongsari).
     * Logika Anda di sini sudah bagus, saya hanya membuatnya sedikit lebih ringkas.
     */
    public function index(Request $request)
    {
        // Tentukan lokasi berdasarkan URL: jika ada 'lengkongsari', maka 'lengkongsari', jika tidak, maka 'tawang'.
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        
        // Ambil data Ruangan dari database, HANYA yang lokasinya sesuai dan urutkan berdasarkan nama.
        $rooms = Room::where('lokasi', $lokasi)->orderBy('name')->get();
        
        // Kirim data yang sudah difilter ke view.
        return view('pages.room.index', compact('rooms'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     * Method ini yang sebelumnya kosong dan menyebabkan halaman putih.
     */
    public function create(Request $request)
    {
        // Tentukan lokasi berdasarkan URL agar bisa dikirim ke form.
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        
        // Kirim variabel $lokasi ke view untuk digunakan di hidden input form.
        return view('pages.room.create', compact('lokasi'));
    }

    /**
     * Menyimpan ruangan baru ke database dengan penanda lokasi.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form untuk keamanan.
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:rooms,code',
            'lokasi' => 'required|in:tawang,lengkongsari', // Pastikan lokasi yang dikirim valid.
        ]);

        // Jika validasi lolos, simpan data baru ke database.
        Room::create($request->all());

        // Tentukan harus kembali ke halaman mana setelah menyimpan.
        $redirectRoute = $request->lokasi == 'lengkongsari' ? 'lengkongsari.room.index' : 'room.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Ruangan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource. (Biasanya tidak terpakai di admin panel seperti ini)
     */
    public function show(Room $room)
    {
        // Jika suatu saat butuh halaman detail, bisa diisi di sini.
        // return view('pages.room.show', compact('room'));
        return redirect()->route('room.index');
    }

    /**
     * Menampilkan form untuk mengedit ruangan.
     */
    public function edit(Room $room)
    {
        // Cukup kirim data ruangan yang mau diedit ke view.
        return view('pages.room.edit', compact('room'));
    }

    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan kode unik, kecuali untuk data yang sedang diedit itu sendiri.
            'code' => 'required|string|unique:rooms,code,' . $room->id,
        ]);

        $room->update($request->all());

        // Tentukan redirect berdasarkan lokasi dari data yang baru di-update.
        $redirectRoute = $room->lokasi == 'lengkongsari' ? 'lengkongsari.room.index' : 'room.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy(Room $room)
    {
        // Simpan info lokasi sebelum data dihapus, untuk keperluan redirect.
        $lokasi = $room->lokasi;
        $room->delete();

        $redirectRoute = $lokasi == 'lengkongsari' ? 'lengkongsari.room.index' : 'room.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Ruangan berhasil dihapus.');
    }
}

