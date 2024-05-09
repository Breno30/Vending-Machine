<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\web\MachinesController;
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

// Route for ESP32
Route::get('/machines/assign-uuid', [MachinesController::class, 'assignUuid'])->name('machines.assign-uuid');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/machines/register', [MachinesController::class, 'register'])->name('machines.register');
    Route::post('/machines/store', [MachinesController::class, 'store'])->name('machines.store');
    
    Route::get('/machines', [MachinesController::class, 'index'])->name('machines.index');
    Route::get('/machines/{machine}', [MachinesController::class, 'show'])->name('machines.show');
    Route::get('/machines/{machine}/edit', [MachinesController::class, 'edit'])->name('machines.edit');
    Route::put('/machines/{machine}', [MachinesController::class, 'update'])->name('machines.update');
});

require __DIR__.'/auth.php';
