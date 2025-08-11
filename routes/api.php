<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/nasabah', [Api::class, 'getNasabag']);
Route::get('/saldo', [Api::class, 'getSaldo']);
Route::post('transaksi/mitra', [Api::class, 'transaksaMitra']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
