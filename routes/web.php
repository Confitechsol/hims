<?php

use App\Http\Controllers\AppointmentsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.homeScreen');
})->name('dashboard');

Route::get('/doctors', function () {
    return view('home.doctors');
})->name('doctors');

Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments');

Route::get('/userlogin', function () {
    return view('home.userlogin');
})->name('userLogin');

Route::get('/ufpassword', function () {
    return view('home.ufpassword');
})->name('ufPassword');

// Admin Routes
Route::get('/login', function () {
    return view('admin.login');
})->name('login');