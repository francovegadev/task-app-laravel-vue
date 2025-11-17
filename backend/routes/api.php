<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// auth routes
Route::post(uri: '/login', action: 'UserController@login')->name('login');
Route::post(uri: '/register', action: 'UserController@register')->name('register');
Route::post(uri: '/logout', action: 'UserController@logout')->middleware('auth:sanctum')->name('logout');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        "user" => $request->user(),
        "roles" => $request->user()->getRoleNames(),
        "permissions" => $request->user()->getPermissionNames()
    ]);
});

// tasks routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get(uri: '/tasks', action: 'TaskController@index')->name('tasks.index');
    Route::get(uri: '/task/{id}', action: 'TaskController@show')->name('tasks.show');
    Route::post(uri: '/task', action: 'TaskController@store')->name('tasks.store');
    Route::put(uri: '/task/{id}', action: 'TaskController@update')->name('tasks.update');
    Route::delete(uri: '/task/{id}', action: 'TaskController@destroy')->name('tasks.destroy');
    Route::get(uri: '/task-status', action: 'TaskController@status')->name('tasks.status');
});

Route::middleware(['auth:sanctum', 'role:admin'])->get('/admin-area', function () {
    return "Solo admins";
});
