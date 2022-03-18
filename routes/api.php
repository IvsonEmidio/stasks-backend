<?php

use App\Http\Controllers\Api\TasksController;
use Illuminate\Support\Facades\Route;

//Tasks CRUD routes.
Route::post('/tasks', [TasksController::class, 'create']);
Route::get('/tasks', [TasksController::class, 'index']);
Route::put('/tasks', [TasksController::class, 'update']);
Route::delete('/tasks', [TasksController::class, 'cancel']);
