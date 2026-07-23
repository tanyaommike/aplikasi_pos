<?php

use App\Http\Controllers\Api\ProdukApiController;
use Illuminate\Support\Facades\Route;

Route::get('/produk', [ProdukApiController::class, 'index']);
