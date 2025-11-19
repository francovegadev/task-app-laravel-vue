<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
dd([
        'auth_user' => $user,
        'requested_id' => $id,
        'matches' => (int) $user->id === (int) $id,
    ]);
    return (int) $user->id === (int) $id;
});
