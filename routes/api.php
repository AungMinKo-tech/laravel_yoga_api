<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Admin
Route::middleware(['auth:sanctum', 'adminMiddleware'])->group(function () {
    //user
    Route::resource('users', UserController::class)->except('destroy');
    //role
    Route::get('/roles', [RoleController::class, 'index']);

});
//Trainer
Route::middleware(['auth:sanctum', 'trainerMiddleware'])->group(function () {
    //user


});

//Student
Route::middleware(['auth:sanctum', 'studentMiddleware'])->group(function () {


});
