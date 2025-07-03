<?php

use App\Http\Controllers\SaldoController;
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

/*
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
    Route::livewire('/mitrapay', 'transaksimitra.create')->name('mitrapay')->layout('layouts.kosong');


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


    Route::get('laporanumum/export', function () {

        $filePath = storage_path() . '/app/laporan/laporan-umum.xlsx';

        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('export.umum');



    Route::get('laporanmitra/export', function () {

        $filePath = storage_path() . '/app/laporan/mitra.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('export.mitra');

    Route::get('laporanmutasi/export', function () {

        $filePath = storage_path() . '/app/laporan/mutasi.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('export.mutasi');

    Route::get('riwayat/export', function () {

        $filePath = storage_path() . '/app/laporan/riwayat.xlsx';


        if (File::exists($filePath)) {
            return response()->download($filePath);
        }
        abort(404);
    })->name('riwayat.export');
});


Route::group(['middleware' => ['role:mitra']], function () {
    Route::livewire('/home', 'index');
    Route::livewire('/mitra', 'mitra.index')->name("mitra.index");
    Route::livewire('/mitra/tarik', 'mitra.tariksaldo');
    Route::livewire('/mitra/create', 'mitra.create')->name('mitra.create');
    Route::livewire('/mitra/{mitra}/edit', 'mitra.edit')->name('mitra.edit');
    Route::livewire('/mitrapay', 'transaksimitra.create')->name('mitrapay')->layout('layouts.kosong');
    Route::livewire('/laporanumum', 'laporan.umum')->name('laporan.umum');
    Route::livewire('/laporanmitra', 'laporan.mitra')->name('laporan.mitra');
    Route::livewire('/laporanmutasi', 'laporan.mutasi')->name('laporan.mutasi');

    Route::livewire('/riwayat', 'transaksimitra.index')->name('transaksimitra.index')->layout('layouts.kosong');

    Route::get('/mitrahistory', function () {
        return view('livewire.transaksimitra.index');
    });
});


Route::livewire('/nasabah/create', 'nasabah.create')->name('nasabah.create');

Route::livewire('/nasabah/{nasabah}/edit', 'nasabah.edit')->name('nasabah.edit');


*/

Route::get('/', function () {
    if (Auth::check())
        return redirect()->route('home');
    else
        return view('auth.login');
});

Route::group(['middleware' => ['role:admin|petugas']], function () {

    Route::get('/nasabah/{nasabah}/show', \App\Livewire\Nasabah\Show::class)->name('nasabah.show');
    Route::get('/nasabah', \App\Livewire\Nasabah\Index::class)->name('nasabah.index');
    Route::get('/nasabah/{nasabah}/edit', \App\Livewire\Nasabah\Edit::class)->name('nasabah.edit');
    Route::get('/nasabah/create', \App\Livewire\Nasabah\Create::class)->name('nasabah.create');
    Route::get('/nasabah/import', \App\Livewire\Nasabah\Import::class)->name('nasabah.import');

    Route::get('/user/{id}/edit', \App\Livewire\User\Edit::class)->name('user.edit');
    Route::get('/user/create', \App\Livewire\User\Create::class)->name('user.create');
    Route::get('/user', \App\Livewire\User\Index::class)->name('user');






    Route::get('/transaksi/setor', \App\Livewire\Transaksi\Setor::class)->name('transaksi.setor');
    Route::get('/transaksi/tarik', \App\Livewire\Transaksi\Tarik::class)->name('transaksi.tarik');

    Route::get('/laporan/biaya', \App\Livewire\Laporan\Biaya::class)->name('laporan.biaya');
    Route::get('/laporan/transaksi', \App\Livewire\Laporan\Transaksi::class)->name('laporan.transaksi');

    Route::get('/home', \App\Livewire\Dasbord\Index::class)->name('home');
    Route::get('/whatapps', \App\Livewire\Whatsapp\Index::class)->name('whatsapp');
    Route::get('/whatapps/pesan', \App\Livewire\Whatsapp\Pesan::class)->name('whatsapp.pesan');
});


Route::group(
    ['middleware' => ['role:admin|petugas']],
    function () {
        Route::get('/setting', \App\Livewire\Setting\Tabungan::class)->name('setting.tabungan');
        Route::get('/saldo', \App\Livewire\Saldo\Index::class)->name('saldo');
    }
);

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);


Route::get('/ceksaldo', [SaldoController::class, 'cek']);
Route::get('/habis', [SaldoController::class, 'habis']);
