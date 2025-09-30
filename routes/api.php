<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Trainer
Route::middleware(['auth:sanctum', 'trainerMiddleware'])->group(function () {
    //user
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);

    //Admin
    Route::middleware(['auth:sanctum', 'adminMiddleware'])->group(function () {
        //user
        Route::resource('users', UserController::class)->only('index', 'store');
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::post('/subscriptions/{id}', [SubscriptionController::class, 'update']);
        Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
        Route::post('/users/{id}/subscriptions', [SubscriptionController::class, 'assignSubscription']);
        Route::get('/users/{id}/subscriptions', [SubscriptionController::class, 'getSubscription']);
    });

});

//Student
Route::middleware(['auth:sanctum', 'studentMiddleware'])->group(function () {


});
