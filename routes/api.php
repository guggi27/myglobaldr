<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post("/doctors/fetch", [DoctorController::class, "fetch"]);
