<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'bank']);
        Permission::create(['name' => 'mitra']);


        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo('bank');
        $role1->givePermissionTo('mitra');

        $role2 = Role::create(['name' => 'mitra']);
        $role2->givePermissionTo('mitra');

        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::create([
            'name' => 'Tabungan',
            'email' => 'kacang@admin.com',
            'saldo' => 0,
            'password' => Hash::make('kacang879'),
        ]);
        $user->assignRole($role1);
        $user->assignRole($role2);

        $user = User::create([
            'name' => 'Waserda',
            'email' => 'waserda@admin.com',
            'saldo' => 0,
            'password' => Hash::make('admin'),
        ]);

        $user->assignRole($role2);
    }
}
