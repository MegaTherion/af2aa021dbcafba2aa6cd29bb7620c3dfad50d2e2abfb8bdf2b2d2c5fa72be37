<?php

use App\Http\Controllers\ImpresoraController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas para items
Route::post('/items/bulkload', [ItemController::class, 'bulkLoad'])->name('items.bulkload');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('item.show');
Route::get('/items/{id}/calculateTimeProduction', [ItemController::class, 'calculateTimeProduction'])->name('item.calculateTimeProduction');
Route::post('/items/{id}/executeProduction', [ItemController::class, 'executeProduction'])->name('item.executeProduction');
Route::get('/items', [ItemController::class, 'index'])->name('item.index');

// Rutas para impresoras
Route::get('/impresoras/{id}', [ImpresoraController::class, 'show'])->name('impresoras.show');
Route::post('/impresoras/{id}/terminarProduccion', [ImpresoraController::class, 'terminarProduccion'])->name('impresoras.terminarProduccion');
Route::get('/impresoras', [ImpresoraController::class, 'index'])->name('impresoras.index');
