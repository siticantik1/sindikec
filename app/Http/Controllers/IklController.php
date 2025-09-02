<?php

namespace App\Http\Controllers;

use App\Models\Ikl;
use App\Models\Rkl;
use Illuminate\Http\Request;
use PDF;

class IklController extends Controller
{
    /**
     * Menampilkan daftar IKL (Inventaris Kelurahan Lengkongsari).
     */
    public function index(Request $request)
    {
        $lokasi = 'lengkongsari';
        $rkls = Rkl::where('lokasi', $lokasi)->orderBy('name')->get();
        
        $query = Ikl::with('rkl')->whereHas('rkl', function ($q) use ($lokasi) {
            $q->where('lokasi', $lokasi);
        });

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikls = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);

        return view('pages.ikl.index', compact('ikls', 'rkls', 'selectedRkl', 'lokasi'));
    }

    /**
     * Menampilkan form untuk membuat IKL baru.
     */
    public function create()
    {
        $rkls = Rkl::where('lokasi', 'lengkongsari')->orderBy('name')->get();
        
        // ======================================================
        // PERBAIKAN UTAMA: Variabel $lokasi didefinisikan
        // dan dikirim ke view agar bisa digunakan di judul.
        // ======================================================
        $lokasi = 'lengkongsari'; 
        return view('pages.ikl.create', compact('rkls', 'lokasi'));
    }

    /**
     * Menyimpan data IKL baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikls,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        Ikl::create($validatedData);

        return redirect()->route('lengkongsari.ikl.index', ['rkl_id' => $request->rkl_id])
                         ->with('success', 'Barang IKL baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data barang IKL.
     */
    public function edit(Ikl $ikl)
    {
        $rkls = Rkl::where('lokasi', 'lengkongsari')->orderBy('name')->get();
        $lokasi = 'lengkongsari'; // Kirim juga variabel lokasi untuk konsistensi
        return view('pages.ikl.edit', compact('ikl', 'rkls', 'lokasi'));
    }

    /**
     * Memperbarui data barang IKL di database.
     */
    public function update(Request $request, Ikl $ikl)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikls,kode_barang,' . $ikl->id,
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            'merk_model' => 'nullable|string|max:255',
            'bahan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $ikl->update($validatedData);

        return redirect()->route('lengkongsari.ikl.index', ['rkl_id' => $ikl->rkl_id])
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang IKL dari database.
     */
    public function destroy(Ikl $ikl)
    {
        $rklId = $ikl->rkl_id;
        $ikl->delete();

        return redirect()->route('lengkongsari.ikl.index', ['rkl_id' => $rklId])
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan RKL lain.
     */
    public function move(Request $request, Ikl $ikl)
    {
        $request->validate([
            'new_rkl_id' => 'required|exists:rkls,id',
        ]);

        $ikl->rkl_id = $request->new_rkl_id;
        $ikl->save();

        return back()->with('success', 'Barang berhasil dipindahkan.');
    }

    /**
     * Membuat laporan PDF khusus untuk IKL Lengkongsari.
     */
    public function pdf(Request $request)
    {
        $lokasi = 'lengkongsari';
        $query = Ikl::with('rkl')->whereHas('rkl', function ($q) use ($lokasi) {
            $q->where('lokasi', $lokasi);
        });

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikls = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        $pdf = PDF::loadView('pages.ikl.print', compact('ikls', 'selectedRkl', 'tanggalCetak', 'lokasi'));
        
        $fileName = 'laporan-ikl-' . $lokasi . '-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}

