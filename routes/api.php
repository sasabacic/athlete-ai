<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TrainingController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

//Trainings Route
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/trainings',[TrainingController::class,'index']);
    Route::post('/trainings',[TrainingController::class,'store']);
});