<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    function login(Request $request): JsonResponse
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|min:4",
        ],
        [
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe contener mínimo 4 caracteres.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->send_error(message: "Error credenciales incorrectas.", code: 422);
        }
        $user = Auth::user();
        $token = $user->createToken('mytoodsecretkeyfvega')->plainTextToken;
        return $this->send_response([
            'user' => new UserResource($user),
            'token' => $token
        ], message: 'Usuario logueado correctamente.');
    }

    function register(Request $request): JsonResponse
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed",
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            200
        ]);
        $user->assignRole('editor');
        $user->givePermissionTo('ver tarea');

        if ($user) {
            // return $this->send_response(data: $user, message: "Usuario registrado correctamente.");
            return $this->send_response(data: ['user' => new UserResource($user)], message: "Usuario registrado correctamente.");
        }

        return $this->send_error(message: 'Error al intentar registrar usuario.');
    }

    function logout(): JsonResponse
    {
        if (Auth::user()) {
            Auth::user()->session_destroy;
            return $this->send_success(data: true, message: 'Sesión cerrada correctamente.');
        }
        return $this->send_error(message: "Error al cerrar sesión.");
    }

    function index() : AnonymousResourceCollection 
    {
        $users = User::all();
        return UserResource::collection($users);     
    }
}
