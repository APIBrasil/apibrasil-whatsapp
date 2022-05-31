<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create all permissions

        if(Permission::count() == 0){
            Permission::create(['name' => 'usuarios-todos', 'guard_name' => 'web']);
            Permission::create(['name' => 'seguranca-menu', 'guard_name' => 'web']);
            Permission::create(['name' => 'postagens-menu', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-todas', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-grupos', 'guard_name' => 'web']);
            Permission::create(['name' => 'mensagens-todas', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-deletar', 'guard_name' => 'web']);
            Permission::create(['name' => 'postagem-adicionar', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-menu', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-menu', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-create', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-store', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-show', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-edit', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-update', 'guard_name' => 'web']);
            Permission::create(['name' => 'usuarios-destroy', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-iniciar', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-deletar', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-comprar-slot', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-visualizar', 'guard_name' => 'web']);
            Permission::create(['name' => 'dashboard-infos', 'guard_name' => 'web']);
            Permission::create(['name' => 'dashboard-geral', 'guard_name' => 'web']);
            Permission::create(['name' => 'seguranca-criar', 'guard_name' => 'web']);
            Permission::create(['name' => 'seguranca-editar', 'guard_name' => 'web']);
            Permission::create(['name' => 'seguranca-deletar', 'guard_name' => 'web']);
            Permission::create(['name' => 'sessoes-editar', 'guard_name' => 'web']);
            Permission::create(['name' => 'logs-menu', 'guard_name' => 'web']);
        }

    }
}
