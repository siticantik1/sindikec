<?php

namespace App\Http\Controllers;

use App\Models\Rkl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RklController extends Controller
{
    /**
     * Menampilkan daftar semua ruangan untuk Lengkongsari.
     */
    public function index()
    {
        $rkls = Rkl::where('lokasi', 'lengkongsari')->orderBy('name')->get();
        return view('pages.rkl.index', compact('rkls'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function create()
    {
        return view('pages.rkl.create');
    }

    /**
     * Menyimpan ruangan baru ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Validasi disesuaikan dengan nama kolom di database ('kode_ruangan')
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode_ruangan' => 'required|string|max:50|unique:rkls,kode_ruangan',
        ]);

        // Tambahkan lokasi secara otomatis
        $validatedData['lokasi'] = 'lengkongsari';
        
        Rkl::create($validatedData);

        return redirect()->route('lengkongsari.rkl.index')
                         ->with('success', 'Data ruangan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit ruangan.
     */
    public function edit(Rkl $rkl)
    {
        return view('pages.rkl.edit', compact('rkl'));
    }

    /**
     * Memperbarui data ruangan di database.
     */
    public function update(Request $request, Rkl $rkl)
    {
        // PERBAIKAN UTAMA: Validasi dan nama field disesuaikan dengan nama kolom di database ('kode_ruangan')
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kode_ruangan' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rkls', 'kode_ruangan')->ignore($rkl->id),
            ],
        ]);
        
        $rkl->update($validatedData);

        return redirect()->route('lengkongsari.rkl.index')
                         ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus data ruangan dari database.
     */
    public function destroy(Rkl $rkl)
    {
        $rkl->delete();

        return redirect()->route('lengkongsari.rkl.index')
                         ->with('success', 'Data ruangan berhasil dihapus.');
    }
}


