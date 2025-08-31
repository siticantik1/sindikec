<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\RoomController;
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


// =================== ROUTE UNTUK INVENTARIS ===================

// Letakkan route yang lebih spesifik di bagian atas

// Route untuk mencetak PDF
Route::get('/inventaris/print', [InventarisController::class, 'pdf'])->name('inventaris.print');

// Route untuk memproses pemindahan ruangan (method PUT)
Route::put('/inventaris/{id}/move', [InventarisController::class, 'move'])->name('inventaris.move');

// Route resource untuk semua proses CRUD (index, create, store, show, edit, update, destroy)
// Ini harus di bawah route custom agar tidak bentrok.
Route::resource('inventaris', InventarisController::class);


// =================== ROUTE UNTUK RUANGAN (ROOM) ===================

// Route untuk mencetak PDF daftar ruangan
Route::get('/room/cetak', [RoomController::class, 'printPDF'])->name('room.cetak');

// Route resource untuk CRUD Ruangan
Route::resource('room', RoomController::class);