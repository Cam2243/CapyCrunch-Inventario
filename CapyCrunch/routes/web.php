<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;

Route::get('/', [InventarioController::class, 'index'])->name('inicio');

Route::post('/apertura', [InventarioController::class, 'apertura'])->name('apertura');

Route::post('/producto/agregar', [InventarioController::class, 'agregarProducto'])->name('producto.agregar');

Route::post('/venta', [InventarioController::class, 'registrarVenta'])->name('venta.registrar');

Route::get('/cierre', [InventarioController::class, 'cierre'])->name('cierre');

Route::post('/reiniciar', [InventarioController::class, 'reiniciar'])->name('reiniciar');