<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Room;
use Illuminate\Http\Request;
use PDF;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar item inventaris sesuai lokasi (Tawang/Lengkongsari).
     */
    public function index(Request $request)
    {
        // DIUBAH: Tentukan lokasi berdasarkan URL yang diakses
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';

        // DIUBAH: Ambil ruangan HANYA dari lokasi yang sesuai untuk dropdown filter
        $rooms = Room::where('lokasi', $lokasi)->orderBy('name')->get();
        
        // DIUBAH: Mulai query inventaris HANYA dari lokasi yang sesuai
        $query = Inventaris::with('room')->where('lokasi', $lokasi);

        // Logika filter ruangan Anda tetap sama dan berfungsi baik
        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);

        return view('pages.inventaris.index', [
            'inventaris' => $inventaris,
            'rooms' => $rooms,
            'selectedRoom' => $selectedRoom,
        ]);
    }

    /**
     * Menampilkan form untuk membuat barang baru sesuai lokasi.
     */
    public function create(Request $request)
    {
        // DIUBAH: Tentukan lokasi dan ambil ruangan yang sesuai saja
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        $rooms = Room::where('lokasi', $lokasi)->orderBy('name')->get();
        
        // DIUBAH: Kirim variabel $lokasi ke view untuk digunakan di hidden input form
        return view('pages.inventaris.create', compact('rooms', 'lokasi'));
    }

    /**
     * Menyimpan data barang baru ke database dengan penanda lokasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'kode_barang' => 'required|string|unique:inventaris,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'lokasi' => 'required|in:tawang,lengkongsari', // DIUBAH: Tambahkan validasi untuk lokasi
        ]);

        Inventaris::create($request->all());

        // DIUBAH: Redirect ke halaman yang benar sesuai lokasi
        $redirectRoute = $request->lokasi == 'lengkongsari' ? 'lengkongsari.inventaris.index' : 'inventaris.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit($id)
    {
        $item = Inventaris::findOrFail($id);

        // DIUBAH: Ambil ruangan HANYA dari lokasi barang yang sedang diedit
        $rooms = Room::where('lokasi', $item->lokasi)->orderBy('name')->get();
        
        return view('pages.inventaris.edit', compact('item', 'rooms'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'kode_barang' => 'required|string|unique:inventaris,kode_barang,' . $item->id,
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
        ]);

        $item->update($request->all());
        
        // DIUBAH: Redirect ke halaman yang benar sesuai lokasi barang yang diupdate
        $redirectRoute = $item->lokasi == 'lengkongsari' ? 'lengkongsari.inventaris.index' : 'inventaris.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy($id)
    {
        $item = Inventaris::findOrFail($id);
        $lokasi = $item->lokasi; // DIUBAH: Simpan info lokasi sebelum item dihapus
        $item->delete();

        // DIUBAH: Redirect ke halaman yang benar
        $redirectRoute = $lokasi == 'lengkongsari' ? 'lengkongsari.inventaris.index' : 'inventaris.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan lain. (Tidak perlu diubah, sudah benar)
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
        ]);

        $item = Inventaris::findOrFail($id);
        $item->room_id = $request->new_room_id;
        $item->save();

        return back()->with('success', 'Barang berhasil dipindahkan.');
    }

    /**
     * Membuat laporan PDF sesuai lokasi.
     */
    public function pdf(Request $request)
    {
        // DIUBAH: Tentukan lokasi dan filter query utama
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        $query = Inventaris::with('room')->where('lokasi', $lokasi);

        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        $pdf = PDF::loadView('pages.inventaris.pdf', [
            'inventaris' => $inventaris,
            'selectedRoom' => $selectedRoom,
            'tanggalCetak' => $tanggalCetak,
        ]);
        
        // DIUBAH: Tambahkan nama lokasi di nama file PDF agar lebih jelas
        $fileName = 'laporan-inventaris-' . $lokasi . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
    
    // Method show() tidak saya sertakan karena biasanya tidak perlu diubah,
    // tapi jika Anda membutuhkannya, logikanya sama.
}

