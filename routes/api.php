<?php

use App\Http\Controllers\MachinesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('/products', ProductController::class);

Route::apiResource('/machines', MachinesController::class);
Route::post('/machines/{machine}/products', [MachinesController::class, 'addProduct']);
Route::delete('/machines/{machine}/products/{id}', [MachinesController::class, 'removeProduct']);
Route::get('/machines/{machine}/products', [MachinesController::class, 'listProduct']);

Route::apiResource('/transactions', TransactionController::class);
Route::post('/webhook', [TransactionController::class, 'handleWebhook']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
