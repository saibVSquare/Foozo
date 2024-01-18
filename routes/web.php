<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('command', function () {
    Artisan::call('clear:all');
    dd("Done");
});

Route::get('migrate', function () {
    Artisan::call('migrate');
    dd("Done");
});

Route::get('migrate-fresh', function () {
    Artisan::call('migrate:refresh');
    dd("Done");
});

Route::get('db-seed', function () {
    Artisan::call('db:seed');
    dd("Done");
});