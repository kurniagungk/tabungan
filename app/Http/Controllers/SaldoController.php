<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;

use App\Models\Setting;
use App\Whatapps;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    //


    public function cek(Request $request)
    {

        $id  = explode("@", $request->input('no'));


        $token = $request->input('token');

        if ($token != "VGFidW5nYW4gQWxrYWhmaSBTb21hbGFuZ3U=")
            return $data['errors'] = ['status' => 401];


        $pesan = $request->input('pesan');

        if ($pesan == "saldo") {

            $nasabah = Nasabah::where('telepon', $id[0])->first();

            if (empty($nasabah)) {
                Whatapps::create([
                    'nomer' => $id[0],
                    'status' => 'gagal'
                ]);
                return $data['errors'] = ['status' => 403];
            }

            Whatapps::create([
                'nomer' => $id[0],
                'nama' => $nasabah->nama,
                'status' => 'berhasil',
                'jenis' => 'terima'
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Saldo Tabungan',
                'data'    => "Sisa saldo " . $nasabah->nama . " Rp. " . number_format($nasabah->saldo, 2, ",", ".")              // <-- data post
            ], 200);
        } else {
            return $data['errors'] = ['status' => 404];
        }
    }

    public function habis(Request $request)
    {
        $query = 'asd';
        $setting = Setting::where('nama', 'saldo_habis')->first();
        $nama = str_replace('{$nama}', $query, $setting->isi);



        echo strval($nama);
    }
}
