<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Room;
use Illuminate\Http\Request;
use PDF;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar semua item inventaris, bisa difilter berdasarkan ruangan.
     */
    public function index(Request $request)
    {
        $rooms = Room::orderBy('name')->get();
        $query = Inventaris::with('room');

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
     * Menampilkan form untuk membuat barang baru.
     */
    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        return view('pages.inventaris.create', compact('rooms'));
    }

    /**
     * Menyimpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'kode_barang' => 'required|string|unique:inventaris,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            // Tambahkan validasi lain jika perlu
        ]);

        Inventaris::create($request->all());

        return redirect()->route('inventaris.index')
                         ->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari satu item inventaris spesifik.
     */
    public function show($id)
    {
        $item = Inventaris::with('room')->findOrFail($id);
        return view('pages.inventaris.show', ['item' => $item]);
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit($id)
    {
        $item = Inventaris::findOrFail($id);
        $rooms = Room::orderBy('name')->get();
        return view('pages.inventaris.edit', compact('item', 'rooms'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        // Validasi input
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

        return redirect()->route('inventaris.index')
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy($id)
    {
        $item = Inventaris::findOrFail($id);
        $item->delete();

        return redirect()->route('inventaris.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan lain.
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
        ]);

        $item = Inventaris::findOrFail($id);
        $item->room_id = $request->new_room_id;
        $item->save();

        // Menggunakan back() agar kembali ke halaman sebelumnya (misal halaman yang difilter)
        return back()->with('success', 'Barang berhasil dipindahkan.');
    }

    /**
     * Membuat dan mengunduh laporan inventaris dalam format PDF.
     */
    public function pdf(Request $request)
    {
        $query = Inventaris::with('room');

        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        // Pastikan nama view PDF-nya benar
        $pdf = PDF::loadView('pages.inventaris.pdf', [
            'inventaris' => $inventaris,
            'selectedRoom' => $selectedRoom,
            'tanggalCetak' => $tanggalCetak,
        ]);

        $fileName = 'laporan-inventaris-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}