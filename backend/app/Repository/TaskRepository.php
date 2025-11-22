<?php

namespace App\Repository;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskRepository
{

  /**
   * get a task
   * @param int $id
   * @return Task
   */
  function find(int $id): ?Task
  {
    return Task::with('user')->find($id);
  }

  /**
   * get all tasks
   * @return AnonymousResourceCollection|array
   */
  function all(): AnonymousResourceCollection | array
  {
    $user = Auth::user();
    if ($user->hasRole('admin')) {
      return TaskResource::collection(Task::with('user')->orderBy('id', 'asc')->get());
    }
    return TaskResource::collection(Task::with('user')->where("user_id", $user->id)->orderBy('id', 'asc')->get());
  }

  /**
   * create a task
   * @param Request $tasks
   * @return Collection|TaskResource
   */
  function create(Request $tasks): Collection | TaskResource
  {
    $task = Task::create($tasks->except('image'));
    $this->uploadImage($tasks, task: $task);
    return new TaskResource($task);
  }

  /**
   * update a task
   * @param Request $tasks
   * @param int $id
   * @return Collection|TaskResource
   */
  function update(Request $tasks, int $id): Collection | TaskResource
  {
    $task = Task::findOrFail($id);
    $task->update($tasks->all());

    return new TaskResource($task);
  }

  /**
   * delete a task
   * @param int $id
   * @return Collection|TaskResource
   */
  function delete(int $id): Collection | TaskResource
  {
    $task = Task::find($id);
    $task->delete();
    return new TaskResource($task);
  }

  /**
   * get a status
   * @return Collection|array
   */
  function getStatus(): Collection | array
  {
    return [
      'completed',
      'in_progress',
      'pending'
    ];
    // return Task::STATUS;
  }

  function uploadImage(Request $request, Task $task): JsonResponse
  {
    if ($request->hasFile('image')) {

      $file = $request->file('image');
      $path = $file->store('tasks', 'public');

      if ($task->image) {
        Storage::disk($task->image->disk)->delete($task->image->path);
        $task->image()->delete();
      }
  
  
      $image = $task->image()->create([
        'path' => $path,
        'disk' => 'public'
      ]);
  
      return response()->json([
        'message' => 'Imagen subida correctamente',
        'image_url' => $image->url(),
        'image_path' => $image->path,
      ]);
    }

    return response()->json([
      'Error' => 'No se pudo subir la imagen.'
    ]);
  }
}
