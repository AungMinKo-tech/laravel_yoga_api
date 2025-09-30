<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Trainer
Route::middleware(['auth:sanctum', 'trainerMiddleware'])->group(function () {
    //user


    //Admin
    Route::middleware(['auth:sanctum', 'adminMiddleware'])->group(function () {
        //user
        Route::resource('users', UserController::class)->only('index', 'store');

    });

});

//Student
Route::middleware(['auth:sanctum', 'studentMiddleware'])->group(function () {


});
