<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



Route::get('/',          HomeController::class);

Route::get('/students',  [StudentController::class, 'index']);
Route::get('/getdata',   [StudentController::class, 'getdata'])->name('students.getdata');
