<?php

namespace Database\Seeders;

use App\Models\Saldo;
use App\Models\WhatsappPesan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PesanWhatsapp extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'jenis' => 'tarik',
                'pesan' => "📤 *Tarik Tunai Berhasil*\n\nNama: {nama}\nJumlah: {jumlah}\nTanggal: {tanggal}\n💰 Sisa Saldo: {saldo}\n📝 Keterangan: {keterangan}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
            [
                'jenis' => 'setor',
                'pesan' => "📥 *Setor Tunai Berhasil*\n\nNama: {nama}\nJumlah: {jumlah}\nTanggal: {tanggal}\n💰 Sisa Saldo: {saldo}\n📝 Keterangan: {keterangan}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
            [
                'jenis' => 'saldo',
                'pesan' => "📊 Cek Saldo\n\nNama: {nama}\n\n💰 Saldo Saat Ini: Rp. {saldo}\n\nTanggal: {tanggal}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
            [
                'jenis' => 'mutasi',
                'pesan' => "📑 Mutasi Rekening\n\nNama: {nama}\n\n💰 Saldo Saat Ini: Rp. {saldo}\n\nTanggal Cetak: {tanggal}\n\nTransaksi Terakhir:\n\n{mutasi}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
        ];


        foreach ($templates as $template) {
            WhatsappPesan::firstOrCreate(
                ['jenis' => $template['jenis']], // cek berdasarkan kode
                [
                    'pesan' => $template['pesan'],
                    'status' => $template['status'],
                ]
            );
        }

        $dataSaldo = Saldo::get();

        // dd($dataSaldo);

        foreach ($dataSaldo as $s) {



            $defaultPesan = WhatsappPesan::whereNull('saldo_id')
                ->get();

            foreach ($defaultPesan as $pesan) {
                WhatsappPesan::firstOrCreate([
                    'saldo_id' => $s->id,
                    'jenis' => $pesan->jenis,
                ], [

                    'pesan' => $pesan->pesan,

                    'status' => $pesan->status
                ]);
            }
        }
    }
}
