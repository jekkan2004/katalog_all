<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\FasilitasController;

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


Route::apiResource('produks', App\Http\Controllers\API\ProdukController::class);
Route::apiResource('fasilitas', App\Http\Controllers\API\FasilitasController::class);

