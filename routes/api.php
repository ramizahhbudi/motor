<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MotorController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\User;

Route::get('/motors', [MotorController::class, 'index']);        // GET all motors
Route::post('/motors/create', [MotorController::class, 'store']);       // POST to create a new motor
Route::get('/motors/{id}', [MotorController::class, 'show']); // GET a specific motor by ID
Route::put('/motors/edit/{id}', [MotorController::class, 'update']); // PUT to update a specific motor by ID
Route::delete('/motors/delete/{id}', [MotorController::class, 'destroy']); // DELETE a specific motor by ID
Route::post('/motors/restore/{id}', [MotorController::class, 'restore']); // POST to restore a soft deleted motor