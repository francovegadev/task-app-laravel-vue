<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends BaseController
{
    public function index()
    {
        $usersCount = User::count();
        $users = User::with('roles')->get();

        $rolesCount = Role::count();
        $roles = Role::with('permissions')->get();

        $permissionsCount = Permission::count();
        $permissions = Permission::all();

        // Cantidad tareas por usuario
        $tasksByUserCount = Task::with('user')->select('user_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user_id')
            ->get();

        // Todas las tareas por usuario
        $tasksByUser = User::with('tasks')->get();

        // Tareas por estado agrupadas por usuario
        $tasksByStatus = Task::with('user')->select('user_id', 'status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('user_id', 'status')
            ->get();

        return response()->json([
            'usuarios' => [
                'total' => $usersCount,
                'data' => $users,
            ],

            'roles' => [
                'total' => $rolesCount,
                'data' => $roles,
            ],

            'permisos' => [
                'total' => $permissionsCount,
                'data' => $permissions,
            ],

            'tareas' => [
                'por_usuario' => [
                    'cantidad' => $tasksByUserCount,
                    'data' => $tasksByUser
                ],
                'por_estado' => $tasksByStatus,
            ],
        ]);
    }
}
