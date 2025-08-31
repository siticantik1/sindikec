<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\IklController;
use App\Http\Controllers\RklController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');


// = "================== ROUTE UNTUK INVENTARIS (UMUM/TAWANG) ==================="

// Route untuk mencetak PDF
Route::get('/inventaris/print', [InventarisController::class, 'pdf'])->name('inventaris.print');
// Route untuk memproses pemindahan ruangan
Route::put('/inventaris/{id}/move', [InventarisController::class, 'move'])->name('inventaris.move');
// Route resource untuk CRUD
Route::resource('inventaris', InventarisController::class);


// =================== ROUTE UNTUK RUANGAN (UMUM/TAWANG) ===================

// Route untuk mencetak PDF daftar ruangan
Route::get('/room/cetak', [RoomController::class, 'printPDF'])->name('room.cetak');
// Route resource untuk CRUD Ruangan
Route::resource('room', RoomController::class);


// =================== ROUTE UNTUK RKL (UMUM/TAWANG) ===================

// PENTING: Route ini HARUS berada SEBELUM Route::resource
Route::get('/rkl/cetak', [RklController::class, 'cetak'])->name('rkl.cetak');
Route::resource('rkl', RklController::class);


// ====================================================================
// =================== ROUTE UNTUK KELURAHAN LENGKONGSARI ===================
// ====================================================================

// Grup ini membuat semua URL diawali '/lengkongsari' dan nama route diawali 'lengkongsari.'
Route::prefix('lengkongsari')->name('lengkongsari.')->group(function () {
    
    // --- Route untuk IKL Lengkongsari ---
    // Route custom harus di atas resource
    Route::get('/ikl/print', [IklController::class, 'pdf'])->name('ikl.print');
    Route::put('/ikl/{id}/move', [IklController::class, 'move'])->name('ikl.move');
    // Route resource untuk CRUD IKL (create, edit, destroy, dll)
    Route::resource('ikl', IklController::class);

    // --- Route untuk RKL Lengkongsari ---
    // Route custom harus di atas resource
    Route::get('/rkl/cetak', [RklController::class, 'pdf'])->name('rkl.cetak');
    Route::put('/rkl/{id}/move', [RklController::class, 'move'])->name('rkl.move');
    // Route resource untuk CRUD RKL (create, edit, destroy, dll)
    Route::resource('rkl', RklController::class);
    
});

