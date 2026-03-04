<?php

namespace Tests\Feature\Livewire\Whatsapp;

use App\Livewire\Whatsapp\Notifikasi;
use App\Models\Nasabah;
use App\Models\Saldo;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\KirimPesanWhatsappJob;
use Livewire\Livewire;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_creates_whatsapp_records_for_selected_nasabah(): void
    {
        $saldo = Saldo::create([
            'nama' => 'Lembaga A',
            'nama_sesion' => 'Lembaga A',
        ]);

        Setting::create([
            'saldo_id' => $saldo->id,
            'nama' => 'whatsapp_api',
            'isi' => 1,
        ]);

        $nasabah = Nasabah::create([
            'rekening' => 'NSB00001',
            'saldo_id' => $saldo->id,
            'nama' => 'Budi',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'Laki-Laki',
            'telepon' => '08123456789',
            'wa' => true,
        ]);

        $user = User::factory()->create([
            'saldo_id' => $saldo->id,
        ]);

        $this->actingAs($user);
        Queue::fake();

        Livewire::test(Notifikasi::class)
            ->set('message', 'Halo {nama}')
            ->set('selected', [$nasabah->id])
            ->call('send')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('whatsapp', [
            'nasabah_id' => $nasabah->id,
            'jenis' => 'notifikasi',
            'status' => 'pending',
        ]);

        Queue::assertPushed(KirimPesanWhatsappJob::class);
    }
}
