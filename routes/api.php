<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;


Route::post("/save/application",[ApplicationController::class,"SaveApplication"]);
Route::get("/fetch/applications",[ApplicationController::class,"fetchApplications"]);
