<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repository\TaskRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends BaseController
{
    public function __construct(protected TaskRepository $taskModel) {
      $this->middleware(["permission:ver tarea"])->only(["index", "show"]);
      $this->middleware(["permission:crear tarea"])->only("store");
      $this->middleware(["permission:editar tarea"])->only("update");
      $this->middleware(["permission:eliminar tarea"])->only("destroy");
    }

    /**
     * get all tasks
     * @return AnonymousResourceCollection
     */
    function index() : AnonymousResourceCollection 
    {
       return $this->taskModel->all(); 
    }

    /**
     * get a task
     * @param int|string $id
     * @return Task|null
     */
    function show(int|string $id) : ?Task 
    {
       return $this->taskModel->find(id: intval($id)); 
    }

    /**
     * create a task
     * @param TaskStoreRequest $request
     * @return TaskResource
     */
    function store(TaskStoreRequest $request) : TaskResource 
    {
      return new TaskResource($this->taskModel->create(tasks: $request));
    }

    /**
     * create a task
     * @param TaskStoreRequest $request
     * @return TaskResource
     */
    function update(TaskStoreRequest $request, int $id) : TaskResource 
    {
      return new TaskResource($this->taskModel->update(tasks: $request, id: $id));
    }

    /**
     * delete a task
     * @param int $id
     * @return TaskResource
     */
    function destroy(int $id) : TaskResource 
    {
      return new TaskResource($this->taskModel->delete(id: $id));
    }

    /**
     * get status for tasks
     * @return array | JsonResponse
     */
    function status() : array | JsonResponse
    {
      return Task::STATUS;
    }
}
