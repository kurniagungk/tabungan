<?php

namespace Database\Seeders;

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
                'kode' => 'tarik',
                'pesan' => "📤 *Tarik Tunai Berhasil*\n\nNama: {nama}  \nJumlah: {jumlah}  \nTanggal: {tanggal}  \n💰 Sisa Saldo: {saldo}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
            [
                'kode' => 'setor',
                'pesan' => "📥 *Setor Tunai Berhasil*\n\nNama: {nama}  \nJumlah: {jumlah}  \nTanggal: {tanggal}  \n💰 Sisa Saldo: {saldo}\n\nSilakan konfirmasi jika ada yang tidak sesuai.",
                'status' => 'aktif',
            ],
        ];


        foreach ($templates as $template) {
            WhatsappPesan::firstOrCreate(
                ['kode' => $template['kode']], // cek berdasarkan kode
                [
                    'pesan' => $template['pesan'],
                    'status' => $template['status'],
                ]
            );
        }
    }
}
