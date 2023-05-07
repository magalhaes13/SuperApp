<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoTaskUpdateRequest;
use App\Http\Resources\TodoTaskResource;
use App\Models\TodoTask;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TodoTaskController extends Controller
{
    /**
     * TodoTaskController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param TodoTask $todoTask
     * @param TodoTaskUpdateRequest $request
     * @return TodoTaskResource
     * @throws AuthorizationException
     */
    public function update(TodoTask $todoTask, TodoTaskUpdateRequest $request)
    {

        $input = $request->validated();

        $todoTask->fill($input);
        $todoTask->save();

        return new TodoTaskResource($todoTask->fresh());
    }

    /**
     * @param TodoTask $todoTask
     * @throws AuthorizationException
     */
    public function destroy(TodoTask $todoTask)
    {
        $todoTask->delete();
    }
}
