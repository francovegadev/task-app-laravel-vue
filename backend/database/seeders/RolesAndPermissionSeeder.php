<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleEditor = Role::create(['name' => 'editor']);
        $roleViewer = Role::create(['name' => 'viewer']);

        $permisionVer = Permission::create(['name' => 'ver tarea']);
        $permisionEditar = Permission::create(['name' => 'editar tarea']);
        $permisionCrear = Permission::create(['name' => 'crear tarea']);
        $permisionEliminar = Permission::create(['name' => 'eliminar tarea']);


        $roleAdmin->syncPermissions([$permisionVer, $permisionEditar, $permisionCrear, $permisionEliminar]);
        $roleEditor->syncPermissions([$permisionEditar, $permisionCrear, $permisionEliminar]);
        $roleViewer->syncPermissions([$permisionVer]);

        $userAdmin = User::create([
            "name" => 'admin',
            "email" => 'admin@email.com',
            "password" => Hash::make('admin1234'),
        ]);
        $userAdmin->assignRole($roleAdmin);
        $userAdmin->givePermissionTo(Permission::all());

        $userEditor = User::create([
            "name" => 'editor',
            "email" => 'editor@email.com',
            "password" => Hash::make('editor1234'),
        ]);
        $userEditor->assignRole($roleEditor);
        $userEditor->givePermissionTo([$permisionVer, $permisionEditar, $permisionCrear, $permisionEliminar]);

        $userViewer = User::create([
            "name" => 'viewer',
            "email" => 'viewer@email.com',
            "password" => Hash::make('viewer1234'),
        ]);
        $userViewer->assignRole($roleViewer);
        $userViewer->givePermissionTo($permisionVer);

        $userAdmin->tasks()->saveMany(Task::factory(5)->make());
        $userEditor->tasks()->saveMany(Task::factory(3)->make());
        $userViewer->tasks()->saveMany(Task::factory(2)->make());
    }
}
