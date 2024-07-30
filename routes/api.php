<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
 //login register//
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    //user routeları//
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::put('users/{id}/freelancer', [UserController::class, 'updateIsFreelancer']);
    Route::put('users/{id}/admin', [UserController::class, 'updateIsAdmin']);
    Route::put('users/{id}/status', [UserController::class, 'updateStatus']);
    Route::post('users/{id}/description', [UserController::class, 'updateDescription']);



    //task routeları ///
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::post('create-task', [TaskController::class, 'store']);
    Route::put('task-update/{id}', [TaskController::class, 'update']);
    Route::put('updateStatus/{id}', [TaskController::class, 'updateStatus']);
    Route::delete('task-delete/{id}', [TaskController::class, 'destroy']);
    Route::get('users/{userId}/tasks', [TaskController::class, 'tasksByUser']);




    //dashboard kontrolleri
    Route::get('getMonthlyJobVolume', [DashboardController::class, 'getMonthlyJobVolume']);
    Route::get('/getFreelancerLastMonth', [DashboardController::class, 'getTopFreelancerLastMonth']);




});
