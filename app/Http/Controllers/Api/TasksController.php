<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tasks;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TasksController extends Controller
{

    /**
     * Default user id
     * @var int
     */
    private $userId;

    public function __construct()
    {
        $this->userId = 1;
    }

    /**
     * Get all tasks from a user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            //Get all user tasks
            $userTasks = Tasks::where('creator_id', $this->userId)->orderBy('date')->get();

            return response()->json([
                'status' => 1,
                'msg' => 'All data retrieved',
                'data' => $userTasks
            ], 200);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 0,
                'msg' => 'An exception has occurred in database'
            ], 500);
        }
    }

    /**
     * Creates a new task.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        //Validate the incoming data.
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:125',
                'note' => 'required|string|max:255',
                'date' => 'required|string|max:12',
                'time' => 'required|string|max:6'
            ]);
            try {
                //Creates a new task.
                Tasks::create(
                    [
                        'creator_id' => $this->userId,
                        'title' => $validated['title'],
                        'note' => $validated['note'],
                        'date' => $validated['date'],
                        'time' => $validated['time'],
                        'status' => 1
                    ]
                );
                //All done
                return response()->json([
                    'status' => 1,
                    'msg' => 'A new record has successfully created'
                ], 201);
            } catch (QueryException $error) {
                //An unknown error has occurred when inserting on database
                return response()->json([
                    'status' => 0,
                    'msg' => 'Error inserting new item on database'
                ], 500);
            }
        } catch (ValidationException $error) {
            //An error on validation fields has occurred
            return response()->json([
                'status' => 0,
                'msg' => 'Check the fields and try again'
            ], 400);
        }
    }

    /**
     * Remove the specified user task.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel(Request $request): JsonResponse
    {
        //Validate the incoming data
        $validated = $request->validate(
            [
                'id' => 'required|int'
            ]
        );
        //Find the desired task
        $task = Tasks::find($validated['id']);

        if ($task) {
            //Cancel the desired task
            try {
                $task->status = 0;
                $task->save();
                return response()->json([

                    'status' => 1,
                    'msg' => 'Task successfully canceled'
                ], 200);
            } catch (QueryException $error) {
                //Catch query exception
                return response()->json([
                    'status' => 0,
                    'msg' => 'An internal error has occurred'
                ], 500);
            }
        } else {
            //Catch not found exception
            return response()->json([
                'status' => 0,
                'msg' => 'Task not found'
            ], 404);
        }
    }
}
