<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_create_task_admin()
    {
        Role::create(['name' => 'admin']);
        Permission::create(['name' => 'crear tarea']);

        $role = Role::findByName('admin');
        $role->givePermissionTo('crear tarea');

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user, 'sanctum');

        $data = [
            'title' => 'Nueva tarea',
            'description' => 'Descripción de prueba',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'in_progress',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/task', $data);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('crear tarea'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Nueva tarea',
                'status' => 'in_progress'
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nueva tarea',
            'user_id' => $user->id
        ]);
    }

        public function test_fail_create_task_role_viewer()
    {
        Role::create(['name' => 'viewer']);
        Permission::create(['name' => 'ver tarea']);
        Permission::create(['name' => 'crear tarea']);

        $role = Role::findByName('viewer');
        $role->givePermissionTo('ver tarea');

        $user = User::factory()->create();
        $user->assignRole('viewer');

        $this->actingAs($user, 'sanctum');

        $data = [
            'title' => 'Nueva tarea de Error',
            'description' => 'Descripción de prueba para probar error',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'status' => 'in_progress',
            'user_id' => $user->id,
        ];

        $this->assertTrue($user->hasRole('viewer'));
        $this->assertFalse($user->hasPermissionTo('crear tarea'));

        $response = $this->postJson('/api/task', $data);
        $response->assertStatus(403)
            ->assertJsonFragment([
                "message" => "User does not have the right permissions.",
            ]);
    }

    public function test_update_task_admin()
    {
        Role::create(['name' => 'admin']);     
        Permission::create(['name' => 'editar tarea']);

        $role = Role::findByName('admin');
        $role->givePermissionTo('editar tarea');

        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id' => $user->id
        ]);

        $updateData = [
            'title' => 'Tarea actualizada',
            'description' => 'Descripción actualizada',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
            'status' => 'completed',
            'user_id' => $user->id
        ];

        $response = $this->put("/api/task/{$task->id}", $updateData);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('editar tarea'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Tarea actualizada',
                'status' => 'completed'
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Tarea actualizada',
            'user_id' => $user->id
        ]);
    }

    public function test_fail_update_task_dont_pass_user_id()
    {
        Role::create(['name' => 'admin']);     
        Permission::create(['name' => 'editar tarea']);

        $role = Role::findByName('admin');
        $role->givePermissionTo('editar tarea');

        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'user_id' => $user->id
        ]);

        $updateData = [
            'title' => 'Tarea actualizada',
            'description' => 'Descripción actualizada',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
            'status' => 'completed',
            // 'user_id' => $user->id
        ];

        $response = $this->putJson("/api/task/{$task->id}", $updateData);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasPermissionTo('editar tarea'));

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'user_id' => 'The user id field is required.',
            ]);
    }
}
