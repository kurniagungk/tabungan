<?php

use App\Livewire\Dasbord\Index;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role; // Import the Role model
use function Pest\Laravel\actingAs;

it('may display the dashboard statistics for admin role', function () {
    // Create an admin user
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    actingAs($admin);

    Livewire::test(Index::class)
        ->assertSee('Jumlah Nasaba')
        ->assertSee('Saldo')
        ->assertSee('Aktif')
        ->assertSee('Tidak');
});

it('may display the dashboard statistics for petugas role', function () {
    // Create a petugas user
    $petugas = User::factory()->create();
    // Check if the 'petugas' role exists, if not create one
    if (!Role::where('name', 'petugas')->exists()) {
        Role::create(['name' => 'petugas']);
    }
    $petugas->assignRole('petugas');

    actingAs($petugas);

    Livewire::test(Index::class)
        ->assertSee('Jumlah Nasaba')
        ->assertSee('Saldo')
        ->assertSee('Aktif')
        ->assertSee('Tidak');
});


it('may display the dashboard statistics for regular user', function () {
    // Create a regular user
    $user = User::factory()->create();

    actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Jumlah Nasaba')
        ->assertSee('Saldo')
        ->assertSee('Aktif')
        ->assertSee('Tidak');
});
