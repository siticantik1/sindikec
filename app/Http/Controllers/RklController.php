<?php

namespace App\Http\Controllers;

use App\Models\Rkl; // Pastikan nama model RKL Anda benar
use Illuminate\Http\Request;

class RklController extends Controller
{
    /**
     * Menampilkan daftar RKL sesuai lokasi (Tawang/Lengkongsari).
     */
    public function index(Request $request)
    {
        // DISEMPURNAKAN: Logika deteksi lokasi yang fleksibel
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        
        $rkls = Rkl::where('lokasi', $lokasi)->orderBy('name')->get();
        return view('pages.rkl.index', compact('rkls'));
    }

    /**
     * Menampilkan form untuk membuat RKL baru.
     */
    public function create(Request $request)
    {
        // DISEMPURNAKAN: Lokasi ditentukan secara dinamis
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        
        return view('pages.rkl.create', compact('lokasi'));
    }

    /**
     * Menyimpan RKL baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:rkls,code',
            // DISEMPURNAKAN: Validasi lokasi dibuat dinamis
            'lokasi' => 'required|in:tawang,lengkongsari',
        ]);

        Rkl::create($request->all());

        // DISEMPURNAKAN: Redirect akan otomatis menyesuaikan dengan lokasi
        $redirectRoute = $request->lokasi == 'lengkongsari' ? 'lengkongsari.rkl.index' : 'rkl.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'RKL baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit RKL.
     */
    public function edit(Request $request, Rkl $rkl)
    {
        // DISEMPURNAKAN: Mengirim juga variabel lokasi ke view edit
        $lokasi = $request->is('lengkongsari/*') ? 'lengkongsari' : 'tawang';
        return view('pages.rkl.edit', compact('rkl', 'lokasi'));
    }

    /**
     * Memperbarui data RKL di database.
     */
    public function update(Request $request, Rkl $rkl)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:rkls,code,' . $rkl->id,
        ]);

        $rkl->update($request->all());

        // DISEMPURNAKAN: Redirect otomatis berdasarkan lokasi dari data yang di-update
        $redirectRoute = $rkl->lokasi == 'lengkongsari' ? 'lengkongsari.rkl.index' : 'rkl.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Data RKL berhasil diperbarui.');
    }

    /**
     * Menghapus data RKL dari database.
     */
    public function destroy(Rkl $rkl)
    {
        $lokasi = $rkl->lokasi; // Simpan info lokasi sebelum dihapus
        $rkl->delete();

        // DISEMPURNAKAN: Redirect otomatis berdasarkan lokasi dari data yang dihapus
        $redirectRoute = $lokasi == 'lengkongsari' ? 'lengkongsari.rkl.index' : 'rkl.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'RKL berhasil dihapus.');
    }
}

