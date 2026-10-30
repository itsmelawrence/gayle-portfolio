<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::get('/', PortfolioController::class)->name('home');

Route::post('/inquiries', InquiryController::class)
    ->middleware('throttle:5,1')
    ->name('inquiries.store');
