<?php

namespace Tests\Feature\Livewire;


use Livewire\Livewire;

use App\Models\Nasabah;

use Tests\TestCase;


class CreateNasabahTest extends TestCase
{
    /** @test */

    public function test_nama_field_is_required()
    {
        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('nama', '')
            ->call('store')
            ->assertHasErrors('nama');
    }

    public function test_pasword_field_is_required()
    {
        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('pasword', '')
            ->call('store')
            ->assertHasErrors('pasword');
    }

    public function test_pasword_must_be_min_4_characters()
    {
        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('pasword', '12')
            ->call('store')
            ->assertHasErrors(['pasword' => 'min']);
    }

    public function test_jenis_kelamin_is_required()
    {
        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('jenis_kelamin', '')
            ->call('store')
            ->assertHasErrors('jenis_kelamin');
    }

    public function test_tahun_is_required()
    {
        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('tahun', '')
            ->call('store')
            ->assertHasErrors('tahun');
    }
    public function can_create_post()
    {

        Livewire::test(\App\Livewire\Nasabah\Create::class)
            ->set('nisn', '998877')
            ->set('nama', 'Budi')
            ->set('tempat_lahir', 'Jakarta')
            ->set('tanggal_lahir', '2012-05-01')
            ->set('alamat', 'Jl. Anggrek')
            ->set('telepon', '0899988877')
            ->set('jenis_kelamin', 'Laki-Laki')
            ->set('nama_wali', 'Pak Budi')
            ->set('saldo', 0)
            ->set('tahun', '2023')
            ->set('pasword', '1234')
            ->set('card', 'CARD123')
            ->call('store')
            ->assertRedirect();

        $this->assertEquals(1, Nasabah::count());
    }
}
