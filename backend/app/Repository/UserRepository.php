<?php 
namespace App\Repository;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserRepository 
{

  /**
   * get a task
   * @param int $id
   * @return User  
   */
 function find(int $id) :?User 
 {
    return User::find($id);
 }

  /**
   * get all users
   * @return AnonymousResourceCollection|array
   */
  function all() : AnonymousResourceCollection | array 
  {
    $users = User::all();
    return UserResource::collection($users);
  }
}