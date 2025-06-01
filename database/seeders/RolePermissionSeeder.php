<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar semua permission
        $permissions = [
            'manage pesanan',
            'manage order',
            'manage produk',
            'manage keranjang',
            'manage akun',
            'manage penjualan',
            'manage artikel',
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        // Role: user
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userPermissions = ['manage order', 'manage keranjang'];
        $userRole->syncPermissions($userPermissions);

        // Role: admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminPermissions = ['manage pesanan', 'manage produk', 'manage artikel'];
        $adminRole->permissions()->detach();
        $adminRole->syncPermissions($adminPermissions);

        // Role: super_admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminPermissions = ['manage akun', 'manage penjualan'];
        $superAdminRole->syncPermissions($superAdminPermissions);

        // Buat super admin default (jika belum ada)
        $user = User::firstOrCreate([
            'email' => 'super@admin.com'
        ], [
            'name' => 'superadmin',
            'password' => Hash::make('echa1234'),
        ]);

        $user->assignRole($superAdminRole);
    }
}
