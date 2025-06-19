<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Hak Akses (Permissions)
        Permission::firstOrCreate(['name' => 'view projects']);
        Permission::firstOrCreate(['name' => 'manage projects']);
        Permission::firstOrCreate(['name' => 'view procurement']);
        Permission::firstOrCreate(['name' => 'manage procurement']);
        Permission::firstOrCreate(['name' => 'manage suppliers']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage hr']);
        Permission::firstOrCreate(['name' => 'view finance']);
        Permission::firstOrCreate(['name' => 'manage finance']);
        Permission::firstOrCreate(['name' => 'view reports']);
        Permission::firstOrCreate(['name' => 'manage quotations']);
        Permission::firstOrCreate(['name' => 'manage assets']);
        Permission::firstOrCreate(['name' => 'manage quotes']); // Tambahan: izin kelola kutipan
        Permission::firstOrCreate(['name' => 'manage clients']);
        Permission::firstOrCreate(['name' => 'manage settings']);
        Permission::firstOrCreate(['name' => 'manage master_data']);

        // Buat Peran (Roles)
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrator']);
        $roleDirektur = Role::firstOrCreate(['name' => 'Direktur']);
        $roleManajerProyek = Role::firstOrCreate(['name' => 'Manajer Proyek']);
        $roleKeuangan = Role::firstOrCreate(['name' => 'Keuangan']);
        $roleLogistik = Role::firstOrCreate(['name' => 'Logistik']);

        
        $roleDirektur->givePermissionTo(Permission::all());
        $roleManajerProyek->givePermissionTo([/* ..., */ 'manage clients']);


        // Berikan semua hak akses ke Direktur
        $roleDirektur->givePermissionTo(Permission::all());
        

        // Berikan hak akses ke Administrator
        $roleAdmin->givePermissionTo([
            'manage users',
            'manage hr',
            'manage quotes'
        ]);

        // Berikan hak akses untuk Manajer Proyek
        $roleManajerProyek->givePermissionTo([
            'view projects',
            'manage projects',
            'view procurement',
            'manage procurement',
            'manage quotations',
            'manage assets'
        ]);

        // Berikan hak akses untuk Keuangan
        $roleKeuangan->givePermissionTo([
            'view projects',
            'view procurement',
            'view finance',
            'manage finance',
            'view reports'
        ]);

        // Berikan hak akses untuk Logistik
        $roleLogistik->givePermissionTo([
            'view procurement',
            'manage suppliers'
        ]);

        // Berikan peran 'Direktur' ke user pertama (default)
        $user = User::first();
        if ($user && !$user->hasRole('Direktur')) {
            $user->assignRole('Direktur');
        }
    }
}


