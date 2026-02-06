<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Nasabah, Transaksi, User};
use Illuminate\Support\Str;

class Api extends Controller
{
    public function getNasabag(Request $request)
    {
        $card = $request->input('card');
        $nasabah = Nasabah::where('card', $card)->select('nama')->first();


        if (is_null($nasabah)) {
            return response()->json(['message' => 'Not Found.'], 404);
        } else {

            return response()->json([
                'nasabah' => $nasabah->nama
            ]);
        }
    }


    public function getSaldo(Request $request)
    {
        $card = $request->input('card');
        $password = $request->input('password');
        $saldo = Nasabah::where('card', $card)->where('password', $password)->select('saldo')->first();


        if (is_null($saldo)) {
            return response()->json(['message' => 'Not Found.'], 404);
        } else {

            return response()->json([
                'saldo' => number_format($saldo->saldo, 0, ',', '.')
            ]);
        }
    }

    public function getSaldoHistoriByNisn(Request $request)
    {
        $nisn = $request->input('nisn');

        if (!$nisn) {
            return response()->json(['message' => 'nisn is required.'], 400);
        }

        $nasabah = Nasabah::where('nisn', $nisn)->select('id', 'nisn', 'nama', 'saldo')->first();

        if (is_null($nasabah)) {
            return response()->json(['message' => 'Not Found.'], 404);
        }

        $histori = $nasabah->transaksi()
            ->orderByDesc('created_at')
            ->get(['id', 'credit', 'debit', 'keterangan', 'ref', 'ref_id', 'created_at']);

        return response()->json([
            'nisn' => $nasabah->nisn,
            'nama' => $nasabah->nama,
            'saldo' => $nasabah->saldo,
            'saldo_format' => number_format($nasabah->saldo, 0, ',', '.'),
            'histori' => $histori,
        ]);
    }

    public function transaksaMitra(Request $request)
    {



        $card = $request->input('card');
        $password = $request->input('password');
        $jumlah = $request->input('jumlah');
        $nasabah = Nasabah::where('card', $card)->where('password', $password)->select('id', 'saldo')->first();

        if (is_null($nasabah))
            return response()->json(['message' => 'Not Found.'], 404);


        $nilai = $nasabah->saldo - $jumlah;


        if ($nilai < 0 || $nasabah->saldo < 0) {

            return response()->json([
                "status" => "gagal"
            ]);
        }


        $trasaksi = Transaksi::create([
            'id' => Str::uuid(),
            'santri_id' => $nasabah->id,
            'jumlah' => $jumlah,
            'mitra_id' => '1',
            'jenis' => 2,
        ]);


        $nasabah->update(['saldo' => $nilai]);

        $mitra = User::find(1);
        $mitra->saldo -= $nilai;
        $mitra->save();


        return response()->json([
            "status" => "berhasil",
            "saldo" =>  number_format($nasabah->saldo, 0, ',', '.')
        ]);
    }
}
