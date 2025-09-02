<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Room;
use Illuminate\Http\Request;
use PDF;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar item inventaris sesuai lokasi.
     */
    public function index(Request $request)
    {
        $lokasi = 'tawang';
        $rooms = Room::where('lokasi', $lokasi)->orderBy('name')->get();
        
        $query = Inventaris::with('room')->whereHas('room', function ($q) use ($lokasi) {
            $q->where('lokasi', $lokasi);
        });

        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);

        return view('pages.inventaris.index', compact('inventaris', 'rooms', 'selectedRoom'));
    }

    /**
     * Menampilkan form untuk membuat barang baru.
     */
    public function create()
    {
        $lokasi = 'tawang';
        $rooms = Room::where('lokasi', $lokasi)->orderBy('name')->get();
        return view('pages.inventaris.create', compact('rooms'));
    }

    /**
     * Menyimpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'kode_barang' => 'required|string|unique:inventaris,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        Inventaris::create($validatedData);
        
        return redirect()->route('tawang.inventaris.index', ['room_id' => $request->room_id])
                         ->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit(Inventaris $inventaris)
    {
        $rooms = Room::where('lokasi', 'tawang')->orderBy('name')->get();
        return view('pages.inventaris.edit', compact('inventaris', 'rooms'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, Inventaris $inventaris)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'room_id' => 'required|exists:rooms,id',
            'kode_barang' => 'required|string|unique:inventaris,kode_barang,' . $inventaris->id,
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $inventaris->update($validatedData);

        return redirect()->route('tawang.inventaris.index', ['room_id' => $inventaris->room_id])
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy(Inventaris $inventaris)
    {
        $roomId = $inventaris->room_id;
        $inventaris->delete();
        return redirect()->route('tawang.inventaris.index', ['room_id' => $roomId])
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan lain.
     */
    public function move(Request $request, Inventaris $inventaris)
    {
        $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
        ]);

        $inventaris->room_id = $request->new_room_id;
        $inventaris->save();

        return back()->with('success', 'Barang berhasil dipindahkan ke ruangan baru.');
    }

    /**
     * Membuat laporan PDF.
     */
    public function pdf(Request $request)
    {
        $lokasi = 'tawang';
        $query = Inventaris::with('room')->whereHas('room', function ($q) use ($lokasi) {
            $q->where('lokasi', $lokasi);
        });

        if ($request->has('room_id') && $request->room_id != '') {
            $query->where('room_id', $request->room_id);
        }

        $inventaris = $query->latest()->get();
        $selectedRoom = Room::find($request->room_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        // ======================================================
        // PERBAIKAN: Mengubah 'pdf' menjadi 'print' agar cocok dengan nama file Anda.
        // ======================================================
        $pdf = PDF::loadView('pages.inventaris.print', compact('inventaris', 'selectedRoom', 'tanggalCetak'));
        
        $fileName = 'laporan-inventaris-' . $lokasi . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}

