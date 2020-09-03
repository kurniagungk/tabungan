<?php


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['role:admin']], function () {
    Route::livewire('/home', 'index');


    Route::get('/nasabah', function () {
        return view('livewire.nasabah.index');
    });


    Route::livewire('/setor', 'setor.index');
    Route::livewire('/tarik', 'tarik.index');


    Route::livewire('/mitra', 'mitra.index')->name("mitra.index");
    Route::livewire('/mitra/tarik', 'mitra.tariksaldo');
    Route::livewire('/mitra/create', 'mitra.create')->name('mitra.create');
    Route::livewire('/mitra/{mitra}/edit', 'mitra.edit')->name('mitra.edit');
    Route::livewire('/mitrapay', 'transaksimitra.create')->name('mitrapay')->layout('layouts.pembayaran');


    Route::livewire('/nasabah/create', 'nasabah.create')->name('nasabah.create');
    Route::livewire('/nasabah', 'nasabah.index')->name('nasabah.index');
    Route::livewire('/nasabah/{nasabah}/edit', 'nasabah.edit')->name('nasabah.edit');


    Route::livewire('/laporanumum', 'laporan.umum')->name('laporan.umum');
    Route::livewire('/laporanmitra', 'laporan.mitra')->name('laporan.mitra');
    Route::livewire('/laporanmutasi', 'laporan.mutasi')->name('laporan.mutasi');

    Route::livewire('/user', 'admin.account')->name('admin.account');


    Route::get('/tarikmitra', function () {
        return view('livewire.mitra.tariksaldo');
    });


    Route::get('/mitrahistory', function () {
        return view('livewire.transaksimitra.index');
    });
});


Route::group(['middleware' => ['role:mitra']], function () {
    Route::livewire('/home', 'index');
    Route::livewire('/mitra', 'mitra.index')->name("mitra.index");
    Route::livewire('/mitra/tarik', 'mitra.tariksaldo');
    Route::livewire('/mitra/create', 'mitra.create')->name('mitra.create');
    Route::livewire('/mitra/{mitra}/edit', 'mitra.edit')->name('mitra.edit');
    Route::livewire('/mitrapay', 'transaksimitra.create')->name('mitrapay')->layout('layouts.pembayaran');
    Route::livewire('/laporanumum', 'laporan.umum')->name('laporan.umum');
    Route::livewire('/laporanmitra', 'laporan.mitra')->name('laporan.mitra');
    Route::livewire('/laporanmutasi', 'laporan.mutasi')->name('laporan.mutasi');

    Route::livewire('/riwayat', 'transaksimitra.index')->name('transaksimitra.index')->layout('layouts.pembayaran');

    Route::get('/mitrahistory', function () {
        return view('livewire.transaksimitra.index');
    });

    Route::get('laporanumum/export', function () {

        $filePath = storage_path() . '/app/laporan/invoices.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('laporan.umum');



    Route::get('laporanumum/export', function () {

        $filePath = storage_path() . '/app/laporan/mitra.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('laporan.mitra');

    Route::get('laporanmutasi/export', function () {

        $filePath = storage_path() . '/app/laporan/mutasi.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('laporan.mutasi');

    Route::get('riwayat/export', function () {

        $filePath = storage_path() . '/app/laporan/riwayat.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('riwayat.export');
});

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Auth::routes();
