<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IklController extends Controller
{
    /**
     * Menampilkan daftar semua item inventaris, bisa difilter berdasarkan ruangan.
     */
    public function index(Request $request)
    {
        $rkls = Rkl::orderBy('name')->get();
        $query = ikl::with('rkl');

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikl = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);

        return view('pages.ikl.index', [
            'ikl' => $ikl,
            'rkls' => $rkls,
            'selectedRkls' => $selectedrkls,
        ]);
    }

    /**
     * Menampilkan form untuk membuat barang baru.
     */
    public function create()
    {
        $rkls = Rkl::orderBy('name')->get();
        return view('pages.ikl.create', compact('rkls'));
    }

    /**
     * Menyimpan data barang baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikl,kode_barang',
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
            // Tambahkan validasi lain jika perlu
        ]);

        Ikl::create($request->all());

        return redirect()->route('ikl.index')
                         ->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari satu item inventaris spesifik.
     */
    public function show($id)
    {
        $item = Ikl::with('rkl')->findOrFail($id);
        return view('pages.ikl.show', ['item' => $item]);
    }

    /**
     * Menampilkan form untuk mengedit data barang.
     */
    public function edit($id)
    {
        $item = Ikl::findOrFail($id);
        $rkls = Rkl::orderBy('name')->get();
        return view('pages.ikl.edit', compact('item', 'rkls'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, $id)
    {
        $item = Ikl::findOrFail($id);

        // Validasi input
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'rkl_id' => 'required|exists:rkls,id',
            'kode_barang' => 'required|string|unique:ikl,kode_barang,' . $item->id,
            'tahun_pembelian' => 'required|numeric|digits:4',
            'jumlah' => 'required|integer|min:1',
            'harga_perolehan' => 'required|numeric',
            'kondisi' => 'required|in:B,KB,RB',
        ]);

        $item->update($request->all());

        return redirect()->route('ikl.index')
                         ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus data barang dari database.
     */
    public function destroy($id)
    {
        $item = Ikl::findOrFail($id);
        $item->delete();

        return redirect()->route('ikl.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Memindahkan barang ke ruangan lain.
     */
    public function move(Request $request, $id)
    {
        $request->validate([
            'new_rkl_id' => 'required|exists:rkls,id',
        ]);

        $item = Ikl::findOrFail($id);
        $item->rkl_id = $request->new_rkl_id;
        $item->save();

        // Menggunakan back() agar kembali ke halaman sebelumnya (misal halaman yang difilter)
        return back()->with('success', 'Barang berhasil dipindahkan.');
    }

    /**
     * Membuat dan mengunduh laporan inventaris dalam format PDF.
     */
    public function pdf(Request $request)
    {
        $query = Ikl::with('rkl');

        if ($request->has('rkl_id') && $request->rkl_id != '') {
            $query->where('rkl_id', $request->rkl_id);
        }

        $ikl = $query->latest()->get();
        $selectedRkl = Rkl::find($request->rkl_id);
        $tanggalCetak = now()->translatedFormat('d F Y');

        // Pastikan nama view PDF-nya benar
        $pdf = PDF::loadView('pages.ikl.pdf', [
            'ikl' => $ikl,
            'selectedRkl' => $selectedRkl,
            'tanggalCetak' => $tanggalCetak,
        ]);

        $fileName = 'laporan-ikl-' . date('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }
}
