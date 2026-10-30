<?php

use App\Http\Controllers\Api\JarvisLeadController;
use Illuminate\Support\Facades\Route;

Route::get('/jarvis/leads', JarvisLeadController::class)->middleware('throttle:30,1');
