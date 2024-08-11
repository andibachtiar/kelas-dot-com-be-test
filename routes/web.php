<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::view('/', 'welcome');
Route::get('/murid', [HomeController::class, 'murid']);
Route::get('/mentor', [HomeController::class, 'mentor']);
