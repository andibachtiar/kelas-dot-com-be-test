<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/murid', [HomeController::class, 'murid']);
Route::get('/mentor', [HomeController::class, 'mentor']);
