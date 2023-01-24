<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//route resource
Route::resource('/santris', \App\Http\Controllers\SantriController::class);
Route::resource('/students', \App\Http\Controllers\StudentController::class);   
Route::resource('/posts', \App\Http\Controllers\PostController::class);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
