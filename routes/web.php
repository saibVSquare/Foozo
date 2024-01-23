<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('payment', [ProductController::class, 'show'])->name('payment');
Route::post('/process-payment', [ProductController::class, 'processPayment'])->name('payment.process');

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

require __DIR__ . '/auth.php';
