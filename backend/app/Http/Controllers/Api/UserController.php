<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    function login(Request $request): JsonResponse
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->send_response(data: [], message: "Error credenciales incorrectas.");
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
}
