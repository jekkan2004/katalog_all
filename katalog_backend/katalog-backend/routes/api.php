<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProdukController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa daftarin semua route API.
| Route otomatis prefix: /api/...
|
*/

// Route default untuk cek API jalan
Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

// Route::get('/produks', [ProdukController::class, 'getAll']);
// Route::post('/produks', [ProdukController::class, 'store']);
// Route::get('/produks/{produk}', [ProdukController::class, 'show']);
// // Route::put('produks/{id}', [ProdukController::class, 'update']);
// Route::delete('/produks/{produk}', [ProdukController::class, 'destroy']);
Route::apiResource('produks', App\Http\Controllers\API\ProdukController::class);

