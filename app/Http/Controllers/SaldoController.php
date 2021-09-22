<?php

namespace App\Http\Controllers;

use App\Nasabah;
use App\Whatapps;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    //


    public function cek(Request $request)
    {

        $id  = explode("@", $request->input('no'));

        $nasabah = Nasabah::where('telepon', $id[0])->first();

        if (empty($nasabah)) {
            Whatapps::create([
                'nomer' => $id[0],
                'status' => 'gagal'
            ]);
            return $data['errors'] = ['status' => 404];
        }

        Whatapps::create([
            'nomer' => $id[0],
            'nama' => $nasabah->nama,
            'status' => 'berhasil'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Saldo Tabungan',
            'data'    => "Sisa saldo " . $nasabah->nama . " Rp. " . number_format($nasabah->saldo, 2, ",", ".")              // <-- data post
        ], 200);
    }
}
