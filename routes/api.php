<?php

use App\Http\Controllers\Api\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/tasks', [TasksController::class, 'index']);
Route::post('/tasks', [TasksController::class, 'create']);
Route::delete('/tasks', [TasksController::class, 'cancel']);
