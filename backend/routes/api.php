<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
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

Route::post(uri: '/auth/google', action: 'GoogleSPAController@googleLogin')->name('auth.google');
Broadcast::routes(['middleware' => ['auth:sanctum']]);

// tasks routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get(uri: '/tasks', action: 'TaskController@index')->name('tasks.index');
    Route::get(uri: '/task/{id}', action: 'TaskController@show')->name('tasks.show');
    Route::post(uri: '/task', action: 'TaskController@store')->name('tasks.store');
    Route::put(uri: '/task/{id}', action: 'TaskController@update')->name('tasks.update');
    Route::delete(uri: '/task/{id}', action: 'TaskController@destroy')->name('tasks.destroy');
    Route::get(uri: '/task-status', action: 'TaskController@status')->name('tasks.status');
});

Route::get(uri: '/dashboard', action: 'DashboardController@index')->middleware(['auth:sanctum', 'role:admin'])->name('dashboard');

Route::get(uri: '/filter', action: 'ItemController@filter')->name('item.filter');
Route::get(uri: '/items', action: 'ItemController@index')->name('item.index');
Route::get(uri: '/item/{id}', action: 'ItemController@show')->name('item.show');

// users routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get(uri: '/users', action: 'UserController@index')->name('users.index');
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return response()->json([
            "user" => $request->user(),
            "roles" => $request->user()->getRoleNames(),
            "permissions" => $request->user()->getPermissionNames()
        ]);
    });
});

Route::middleware('auth:sanctum')->get('/user/{id}', function (Request $request, int $id) {
    $user = User::find($id);
    return response()->json([
        "user" => $user,
        "roles" => $request->user()->getRoleNames(),
        "permissions" => $request->user()->getPermissionNames()
    ]);
});
