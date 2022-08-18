<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'acesso@whatsapp.com',
            'username' => 'root',
            'password' => '1234'
        ]);

        $role = Role::create(['name' => 'root']);

        $permissions = Permission::pluck('id', 'id')->all();

        // vincula permissoes ao grupo
        $role->syncPermissions($permissions);
        foreach ($permissions as $permission) {
            $user->givePermissionTo($permission);
        }

        $user->assignRole([$role->id]);
    }
}
