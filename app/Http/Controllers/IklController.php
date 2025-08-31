<?php

namespace App\Http\Controllers;

use App\Models\Ikl;   // Pastikan nama model Anda benar
use App\Models\Rkl;   // Pastikan nama model Anda benar
use Illuminate\Http\Request;
use PDF;

class IklController extends Controller
{
    /**
     * Menampilkan daftar item IKL HANYA untuk Lengkongsari.
     */
    public function index(Request $request)
    {
        $lokasi = 'lengkongsari';
        $rkls = Rkl::where('lokasi', $lokasi)->orderBy('name')->get();
        $query = Ikl::with('rkl')->where('lokasi', $lokasi);

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikls = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);

        return view('pages.ikl.index', [
            // DISEMPURNAKAN: Nama variabel disamakan (ikls) agar konsisten dengan controllernya
            'ikls' => $ikls, 
            'rkls' => $rkls,
            'selectedRkl' => $selectedRkl,
        ]);
    }

    /**
     * Menampilkan form untuk membuat barang IKL baru.
     */
    public function create()
    {
        $lokasi = 'lengkongsari';
        $rkls = Rkl::where('lokasi', $lokasi)->orderBy('name')->get();
        
        return view('pages.ikl.create', compact('rkls', 'lokasi'));
    }

    /**
     * Menyimpan data IKL baru dengan lokasi 'lengkongsari'.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikls,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'lokasi' => 'required|in:lengkongsari',
        ]);

        Ikl::create($request->all());

        return redirect()->route('lengkongsari.ikl.index')
                         ->with('success', 'Barang IKL baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data barang IKL.
     */
    public function edit(Ikl $ikl) // DISEMPURNAKAN: Menggunakan Route Model Binding
    {
        // Ambil RKL HANYA dari lokasi barang yang diedit
        $rkls = Rkl::where('lokasi', $ikl->lokasi)->orderBy('name')->get();
        
        return view('pages.ikl.edit', compact('ikl', 'rkls'));
    }

    /**
     * Memperbarui data barang IKL di database.
     */
    public function update(Request $request, Ikl $ikl) // DISEMPURNAKAN: Menggunakan Route Model Binding
    {
        // DISEMPURNAKAN: Validasi dilengkapi agar sama seperti saat membuat data baru
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikls,kode_barang,' . $ikl->id,
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
        ]);

        $ikl->update($request->all());

        return redirect()->route('lengkongsari.ikl.index')
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang IKL dari database.
     */
    public function destroy(Ikl $ikl) // DISEMPURNAKAN: Menggunakan Route Model Binding
    {
        $ikl->delete();

        return redirect()->route('lengkongsari.ikl.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan RKL lain.
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'new_rkl_id' => 'required|exists:rkls,id',
        ]);

        $item = Ikl::findOrFail($id);
        $item->rkl_id = $request->new_rkl_id;
        $item->save();

        return back()->with('success', 'Barang berhasil dipindahkan.');
    }

    /**
     * Membuat laporan PDF khusus untuk IKL Lengkongsari.
     */
    public function pdf(Request $request)
    {
        $lokasi = 'lengkongsari';
        $query = Ikl::with('rkl')->where('lokasi', $lokasi);

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikls = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        $pdf = PDF::loadView('pages.ikl.pdf', [
            // DISEMPURNAKAN: Nama variabel disamakan (ikls)
            'ikls' => $ikls,
            'selectedRkl' => $selectedRkl,
            'tanggalCetak' => $tanggalCetak,
        ]);
        
        $fileName = 'laporan-ikl-' . $lokasi . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}

