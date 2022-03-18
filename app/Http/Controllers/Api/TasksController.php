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

    /**
     * Default error messages
     * @var string[]
     */
    private $defaultErrorMessages;

    public function __construct()
    {
        $this->userId = 1;
        $this->defaultErrorMessages = [
            'database' => 'An exception has occurred in database',
            'validation' => 'Check the fields and try again'
        ];
    }

    /**
     * Get all tasks from a user.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $userTasks = Tasks::where('creator_id', $this->userId)->orderBy('date')->get();
            return response()->json([
                'status' => 1,
                'msg' => 'All data retrieved',
                'data' => $userTasks
            ], 200);
        } catch (QueryException $error) {
            return response()->json([
                'status' => 0,
                'msg' => $this->defaultErrorMessages['database'],
                'data' => []
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
                $createdTask = Tasks::create(
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
                    'msg' => 'A new record has successfully created',
                    'data' => $createdTask
                ], 201);
            } catch (QueryException $error) {
                //An unknown error has occurred when inserting on database
                return response()->json([
                    'status' => 0,
                    'msg' => $this->defaultErrorMessages['database'],
                    'data' => []
                ], 500);
            }
        } catch (ValidationException $error) {
            //An error on validation fields has occurred
            return response()->json([
                'status' => 0,
                'msg' => $this->defaultErrorMessages['validation'],
                'data' => []
            ], 400);
        }
    }

    /**
     * Cancel the specified task.
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel(Request $request): JsonResponse
    {
        //Validate the incoming data
        try {
            $validated = $request->validate(
                [
                    'id' => 'required|int'
                ]
            );

            try {
                //Find the desired task
                $task = Tasks::find($validated['id']);

                //Cancel the desired task
                if ($task) {
                    $task->status = 0;
                    $task->save();
                    return response()->json([
                        'status' => 1,
                        'msg' => 'Task successfully canceled',
                        'data' => $task
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'Task not found',
                        'data' => []
                    ], 404);
                }
            } catch (QueryException $error) {
                //An error has occurred on database.
                return response()->json([
                    'status' => 0,
                    'msg' => $this->defaultErrorMessages['database'],
                    'data' => []
                ], 500);
            }
        } catch (ValidationException $error) {
            //Error on fields validation
            return response()->json([
                'status' => 0,
                'msg' => $this->defaultErrorMessages['validation'],
                'data' => []
            ], 400);
        }
    }

    /**
     * Update the specified task.
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        //Validate the incoming data
        try {
            $validated = $request->validate([
                'id' => 'required|int',
                'title' => 'required|string|max:125',
                'note' => 'required|string|max:255',
                'date' => 'required|string|max:12',
                'time' => 'required|string|max:6'
            ]);

            //Find the desired task and fulfill
            try {
                $task = Tasks::find($validated['id']);
                $task->fill($validated);
                $task->save();
                return response()->json([
                    'status' => 1,
                    'msg' => 'All fields updated successfully',
                    'data' => $task
                ], 200);
            } catch (QueryException $error) {
                //An error has occurred in database
                return response()->json([
                    'status' => 0,
                    'msg' => $this->defaultErrorMessages['database'],
                    'data' => []
                ], 500);
            }
        } catch (ValidationException $error) {
            //Error on fields validation
            return response()->json([
                'status' => 0,
                'msg' => $this->defaultErrorMessages['validation'],
                'data' => []
            ], 400);
        }
    }
}
