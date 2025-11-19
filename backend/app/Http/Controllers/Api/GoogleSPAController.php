<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Traits\HasRoles;

class GoogleSPAController extends Controller
{
    use HasRoles;
    public function googleLogin(Request $request)
    {
        // Credential que viene del frontend (ID Token)
        $credential = $request->input('credential');

        if (!$credential) {
            return response()->json(['error' => 'Token de Google no recibido'], 422);
        }

        // Obtener info del usuario desde Google
        $googleResponse = Http::get("https://oauth2.googleapis.com/tokeninfo", [
            'id_token' => $credential,
        ]);

        if ($googleResponse->failed()) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        $payload = $googleResponse->json();

        // Crear o obtener usuario
        $user = User::firstOrCreate(
           ['email' => $payload['email']],
            [
                'name' => $payload['name'] ?? 'Usuario Google',
                'password' => bcrypt('password')
            ]
        );
        $user->assignRole('editor');
        $user->givePermissionTo('ver tarea');
        $user->givePermissionTo('crear tarea');
        $user->givePermissionTo('editar tarea');
        $user->givePermissionTo('eliminar tarea');

        // Crear token Sanctum
        $token = $user->createToken('google_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ]);
    }
}
